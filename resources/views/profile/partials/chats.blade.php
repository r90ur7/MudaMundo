<script>
window.chatList = function() {
    return {
        chats: [],
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
            if (this.echoChannel) {
                try {
                    this.echoChannel.stopListening('.App\\Events\\NovaMensagemChat');
                } catch (e) { console.warn('stopListening falhou:', e); }
                this.echoChannel = null;
                this.listenerCount = 0;
            }
            fetch(`/chat/${solicitacaoId}`)
                .then(r => r.json())
                .then(data => {
                    this.activeChat = this.chats.find(c => c.solicitacao.id === solicitacaoId);
                    this.messages = data.mensagens.map(m => ({...m, status: 'sent'}));
                    this.$nextTick(() => {
                        const el = document.getElementById('chat-messages');
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                    this.listenToChannel(solicitacaoId);
                });
        },
        closeChat() {
            this.activeChat = null;
            this.messages = [];
            this.newMessage = '';
            this.sendingMessage = false;
            this.pendingMessages = {};
            this.listenerCount = 0;
            if (this.echoChannel) {
                try {
                    this.echoChannel.stopListening('.App\\Events\\NovaMensagemChat');
                } catch (e) { console.warn('stopListening falhou:', e); }
                this.echoChannel = null;
            }
        },
        listenToChannel(solicitacaoId) {
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
                this.echoChannel.listen('.App\\Events\\NovaMensagemChat', (e) => {
                    if (e.mensagem) {
                        const exists = this.messages.some(m => m.id === e.mensagem.id);
                        if (!exists) {
                            this.messages.push(e.mensagem);
                            this.forceUpdateMessages();
                            setTimeout(() => {
                                const chatMessages = document.getElementById('chat-messages');
                                if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
                            }, 100);
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
                this.echoChannel.listenForWhisper('debug', (payload) => {
                    console.log('[Echo] Whisper debug recebido:', payload);
                });
                this.listenerCount++;
                console.log(`[Echo] Listener adicionado. Total: ${this.listenerCount}`);
            }
        },
        forceUpdateMessages() {
            this.messages = JSON.parse(JSON.stringify(this.messages));
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

<div id="chats-list" class="space-y-4">
    <template x-if="chats.length === 0">
        <p class="text-gray-600 dark:text-gray-400 italic">Você não tem nenhuma conversa ativa no momento.</p>
    </template>
    <template x-for="chat in chats" :key="chat.solicitacao.id">
        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow hover:bg-gray-50 dark:hover:bg-gray-600 transition cursor-pointer"
            @click="activeChat && activeChat.solicitacao.id === chat.solicitacao.id ? closeChat() : openChat(chat.solicitacao.id)">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-gray-200 dark:bg-gray-800 rounded-full flex items-center justify-center text-sm text-gray-500 dark:text-gray-300 mr-3">
                        <span x-text="chat.otherUserInitial"></span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                            <span x-text="chat.otherUserInitial"></span>.<span x-text="chat.otherUserCity ? ' - ' + chat.otherUserCity + '/' + chat.otherUserUf : ''"></span>
                        </h4>
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.Echo) {
            console.log('Cliente Echo conectado:', window.Echo.connector.socketId);
            window.Echo.connector.pusher.connection.bind('message', function(data) {
                console.log('[Echo][GLOBAL] Evento bruto recebido:', data);
            });
        } else {
            console.error('Cliente Echo não está conectado. Verifique a configuração.');
        }
    });
</script>
