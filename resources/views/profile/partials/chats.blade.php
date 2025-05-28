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
            // Sempre remove listeners antigos antes de abrir novo chat
            if (this.echoChannel) {
                try {
                    this.echoChannel.stopListening('.App\\Events\\NovaMensagemChat');
                } catch (e) { console.warn('stopListening falhou:', e); }
                this.echoChannel = null;
                this.listenerCount = 0;
            }
            fetch(`/chat/${solicitacaoId}`)
                .then(r => r.json()) // <-- Corrigido aqui
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
                    if (e.mensagem) {
                        // Só adiciona se não existe nenhuma mensagem com o mesmo id
                        const exists = this.messages.some(m => m.id === e.mensagem.id);
                        if (!exists) {
                            this.messages.push(e.mensagem);
                            this.forceUpdateMessages();
                            setTimeout(() => {
                                const chatMessages = document.getElementById('chat-messages');
                                if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
                            }, 100);
                            // Dispara notificação apenas se a mensagem recebida NÃO for do próprio usuário
                            if (e.mensagem.user_id !== this.userId) {
                                window.dispatchEvent(new CustomEvent('chat-notification', {
                                    detail: {
                                        chatId: e.mensagem.solicitacao_id,
                                        chatData: {
                                            otherUserName: e.mensagem.user?.name || '',
                                            preview: e.mensagem.mensagem
                                        }
                                    }
                                }));
                            }
                        }
                    } else {
                        console.log('[Echo] Evento NovaMensagemChat recebido sem payload de mensagem:', e);
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
            const msgToSend = {
                solicitacao_id: this.activeChat.solicitacao.id,
                mensagem: this.newMessage
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
                // Não adiciona nada aqui, só espera o evento chegar
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
            // Removido Proxy global para evitar conflitos
        } else {
            console.error('Cliente Echo não está conectado. Verifique a configuração.');
        }
    });
</script>
