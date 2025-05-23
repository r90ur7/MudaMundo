<script>
window.chatList = function() {
    return {
        chats: [],
        showChatModal: false,
        activeChat: null,
        messages: [],
        newMessage: '',
        userId: {{ auth()->id() }},
        echoChannel: null,
        sendingMessage: false,
        pendingMessages: {},
        listenerCount: 0, // Para depuração
        fetchChats() {
            fetch('/api/chats')
                .then(r => r.json())
                .then(data => { this.chats = data.chats; });
        },
        openChat(solicitacaoId) {
            fetch(`/chat/${solicitacaoId}`)
                .then(r => r.json())
                .then(data => {
                    this.activeChat = this.chats.find(c => c.solicitacao.id === solicitacaoId);
                    this.messages = data.mensagens.map(m => ({...m, status: 'sent'}));
                    this.showChatModal = true;
                    this.$nextTick(() => {
                        const el = document.getElementById('chat-messages');
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                    this.listenToChannel(solicitacaoId);
                });
        },
        closeChat() {
            this.showChatModal = false;
            this.activeChat = null;
            this.messages = [];
            this.newMessage = '';
            this.sendingMessage = false;
            this.pendingMessages = {};
            this.listenerCount = 0;
            // Remove listeners do canal anterior
            if (this.echoChannel) {
                try {
                    this.echoChannel.stopListening('.App\\Events\\NovaMensagemChat');
                } catch (e) { console.warn('stopListening falhou:', e); }
                this.echoChannel = null;
            }
        },
        listenToChannel(solicitacaoId) {
            // Sempre remove listeners antigos antes de adicionar um novo
            if (this.echoChannel) {
                try {
                    this.echoChannel.stopListening('.App\\Events\\NovaMensagemChat');
                } catch (e) { console.warn('stopListening falhou:', e); }
                this.echoChannel = null;
                this.listenerCount = 0;
                console.log('[Echo] Listener antigo removido');
            }
            if (window.Echo) {
                console.log('Abrindo canal privado: chat.' + solicitacaoId);
                this.echoChannel = window.Echo.private(`chat.${solicitacaoId}`);
                console.log('[Echo] Canal privado inscrito:', this.echoChannel);
                // Adiciona listener novo
                this.echoChannel.listen('.App\\Events\\NovaMensagemChat', (e) => {
                    console.log('[Echo] Evento recebido:', e);
                    if (e.mensagem) {
                        // Verifica se a mensagem já existe pelo id ou local_id
                        const idx = this.messages.findIndex(m => (m.id && m.id === e.mensagem.id) || (m.local_id && m.local_id === e.mensagem.local_id));
                        if (idx !== -1) {
                            // Substitui o array inteiro para garantir reatividade
                            this.messages = this.messages.map((m, i) => i === idx ? { ...e.mensagem, status: 'sent' } : m);
                            console.log('[Echo] Mensagem atualizada no array (replace)');
                        } else {
                            // Cria novo array para garantir reatividade
                            this.messages = [...this.messages, { ...e.mensagem, status: 'sent' }];
                            console.log('[Echo] Mensagem adicionada ao array (push)');
                        }
                        this.forceUpdateMessages();
                        this.$nextTick(() => {
                            const el = document.getElementById('chat-messages');
                            if (el) el.scrollTop = el.scrollHeight;
                        });
                    } else {
                        console.warn('[Echo] Evento recebido sem payload de mensagem:', e);
                    }
                });
                // Listener global para debug de todos os eventos
                this.echoChannel.listenForWhisper('debug', (payload) => {
                    console.log('[Echo] Whisper debug recebido:', payload);
                });
                this.listenerCount++;
                console.log(`[Echo] Listener adicionado. Total: ${this.listenerCount}`);
            }
        },
        forceUpdateMessages() {
            // Força Alpine a reprocessar o array
            this.messages = JSON.parse(JSON.stringify(this.messages));
            // Fallback: dispara evento customizado para forçar update se necessário
            this.$dispatch && this.$dispatch('chat-messages-updated');
            console.log('[Echo] Forçando atualização do array de mensagens');
        },
        sendMessage() {
            if (!this.newMessage.trim() || !this.activeChat || this.sendingMessage) return;
            this.sendingMessage = true;
            const localId = 'local-' + Date.now() + '-' + Math.random().toString(36).substr(2, 5);
            const pendingMsg = {
                local_id: localId,
                mensagem: this.newMessage,
                user_id: this.userId,
                created_at: new Date().toISOString(),
                status: 'pending'
            };
            this.messages.push(pendingMsg);
            const msgToSend = {
                solicitacao_id: this.activeChat.solicitacao.id,
                mensagem: this.newMessage,
                local_id: localId
            };
            fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify(msgToSend)
            })
            .then(r => r.json())
            .then(data => {
                this.newMessage = '';
                if (data.mensagem && data.mensagem.local_id) {
                    const idx = this.messages.findIndex(m => m.local_id === data.mensagem.local_id);
                    if (idx !== -1) {
                        this.messages[idx] = { ...data.mensagem, status: 'sent' };
                    }
                } else {
                    for (let i = this.messages.length - 1; i >= 0; i--) {
                        if (this.messages[i].local_id === localId) {
                            this.messages[i].status = 'sent';
                            break;
                        }
                    }
                }
            })
            .finally(() => {
                this.sendingMessage = false;
            });
        },
        formatDate(dateStr) {
            const d = new Date(dateStr);
            return d.toLocaleString('pt-BR', { dateStyle: 'short', timeStyle: 'short' });
        },
        init() {
            this.fetchChats();
            // Listener global para todos os eventos Echo recebidos (debug)
            if (window.Echo) {
                window.Echo.connector.pusher.connection.bind('message', function(data) {
                    console.log('[Echo][GLOBAL] Evento bruto recebido:', data);
                });
                window.Echo.connector.pusher.connection.bind('state_change', function(states) {
                    console.log('[Echo][GLOBAL] Estado da conexão mudou:', states);
                });
                window.Echo.connector.pusher.connection.bind('connected', function() {
                    console.log('[Echo][GLOBAL] Conectado ao Pusher/Ably!');
                });
                window.Echo.connector.pusher.connection.bind('error', function(err) {
                    console.error('[Echo][GLOBAL] Erro de conexão:', err);
                });
                window.Echo.connector.pusher.connection.bind('subscription_error', function(channel, err) {
                    console.error('[Echo][GLOBAL] Erro ao se inscrever no canal:', channel, err);
                });
                window.Echo.connector.pusher.connection.bind('subscription_succeeded', function(channel) {
                    console.log('[Echo][GLOBAL] Inscrição bem-sucedida no canal:', channel);
                });
            }
        }
    }
}
</script>

<div id="chats-list" x-data="chatList()" class="space-y-4">
    <template x-if="chats.length === 0">
        <p class="text-gray-600 dark:text-gray-400 italic">Você não tem nenhuma conversa ativa no momento.</p>
    </template>
    <template x-for="chat in chats" :key="chat.solicitacao.id">
        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow hover:bg-gray-50 dark:hover:bg-gray-600 transition cursor-pointer"
            @click="openChat(chat.solicitacao.id)">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-gray-200 dark:bg-gray-800 rounded-full flex items-center justify-center text-sm text-gray-500 dark:text-gray-300 mr-3">
                        <span x-text="chat.otherUserInitial"></span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100" x-text="chat.otherUserName"></h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300" x-text="chat.mudaNome || '-' "></p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs text-gray-500 dark:text-gray-400" x-text="chat.lastMessageDate ? chat.lastMessageDate : 'Sem mensagens'"></span>
                    <div class="mt-1" x-show="chat.unreadCount > 0">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500">
                            <span class="text-xs font-medium text-white" x-text="chat.unreadCount"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Modal de chat -->
    <div x-show="showChatModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="display: none;">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-lg mx-4 p-0 flex flex-col h-[80vh]">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <div class="h-10 w-10 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-lg text-gray-700 dark:text-gray-200">
                        <span x-text="activeChat?.otherUserInitial"></span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100" x-text="activeChat?.otherUserName"></h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="activeChat?.mudaNome"></p>
                    </div>
                </div>
                <button @click="closeChat" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-4 space-y-2 bg-gray-50 dark:bg-gray-900" id="chat-messages">
                <template x-for="msg in messages" :key="msg.local_id || msg.id">
                    <div :class="msg.user_id === userId ? 'justify-end' : 'justify-start'" class="flex">
                        <div :class="msg.user_id === userId ? 'bg-emerald-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100'" class="rounded-xl px-4 py-2 max-w-xs mb-1 flex items-end gap-2">
                            <span x-text="msg.mensagem"></span>
                            <template x-if="msg.status === 'pending'">
                                <svg class="w-4 h-4 text-yellow-300 animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                            </template>
                            <template x-if="msg.status === 'sent'">
                                <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </template>
                            <div class="text-xs mt-1 text-right opacity-60" x-text="formatDate(msg.created_at)"></div>
                        </div>
                    </div>
                </template>
            </div>
            <form @submit.prevent="sendMessage" class="p-4 border-t border-gray-200 dark:border-gray-700 flex gap-2">
                <input x-model="newMessage" type="text" class="flex-1 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Digite sua mensagem..." autocomplete="off" :disabled="sendingMessage" />
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition" :disabled="sendingMessage || !newMessage.trim()">
                    <span x-show="sendingMessage">
                        <svg class="w-5 h-5 animate-spin mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
                    </span>
                    <span x-show="!sendingMessage">Enviar</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.Echo) {
        console.log('Cliente Echo conectado:', window.Echo.connector.socketId);
        // Listener global para depuração de TODOS os eventos recebidos
        window.Echo.connector.pusher.connection.bind('message', function(data) {
            console.log('[Echo][GLOBAL] Evento bruto recebido:', data);
        });
        // Listener global para eventos de canal privado
        window.Echo.private = new Proxy(window.Echo.private, {
            apply(target, thisArg, argumentsList) {
                const channel = argumentsList[0];
                const result = Reflect.apply(target, thisArg, argumentsList);
                // Adiciona listener global para todos os eventos desse canal
                result.listen('App\\Events\\NovaMensagemChat', (e) => {
                    console.log('[Echo][GLOBAL] Evento NovaMensagemChat recebido no canal', channel, e);
                });
                return result;
            }
        });
    } else {
        console.error('Cliente Echo não está conectado. Verifique a configuração.');
    }
});
</script>
