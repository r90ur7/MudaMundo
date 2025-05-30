import './bootstrap';
import HSThemeAppearance from './theme.js';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

HSThemeAppearance.init();

// Notificação global de chat em qualquer tela
if (window.Echo && window.userId) {
    window.Echo.private('user.' + window.userId)
        .listen('.App\\Events\\NovaMensagemChat', (e) => {
            if (e.mensagem) {
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
        })
        // Notificações de histórico para solicitações
        .listen('.App\\Events\\SolicitacaoCriada', (e) => {
            window.dispatchEvent(new CustomEvent('history-notification', {
                detail: {
                    preview: e.preview || 'Você enviou uma nova solicitação!',
                }
            }));
        })
        .listen('.App\\Events\\SolicitacaoRecebida', (e) => {
            window.dispatchEvent(new CustomEvent('history-notification', {
                detail: {
                    preview: e.preview || 'Você recebeu uma nova solicitação!',
                }
            }));
        })
        .listen('.App\\Events\\SolicitacaoAtualizada', (e) => {
            window.dispatchEvent(new CustomEvent('history-notification', {
                detail: {
                    preview: e.preview || 'Sua solicitação foi atualizada!',
                }
            }));
        })
        .listen('.App\\Events\\SolicitacaoAceita', (e) => {
            window.dispatchEvent(new CustomEvent('history-notification', {
                detail: {
                    preview: e.preview || 'Sua solicitação foi aceita!',
                }
            }));
        })
        .listen('.App\\Events\\SolicitacaoRejeitada', (e) => {
            window.dispatchEvent(new CustomEvent('history-notification', {
                detail: {
                    preview: e.preview || 'Sua solicitação foi rejeitada.',
                }
            }));
        })
        .listen('.App\\Events\\SolicitacaoConcluida', (e) => {
            window.dispatchEvent(new CustomEvent('history-notification', {
                detail: {
                    preview: e.preview || 'Solicitação concluída!',
                }
            }));
        });
}

// Função global para abrir o chat correto ao clicar na notificação
window.openChatFromNotification = function (chatId, chatData) {
    // Redireciona para o profile com hash para a aba de chats e o id do chat
    window.location.href = `/profile#chats-${chatId}`;
};
