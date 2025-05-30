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
        });
}
