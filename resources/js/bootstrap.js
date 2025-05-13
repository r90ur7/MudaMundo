import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Ably from 'ably';

window.Ably = Ably;

window.Echo = new Echo({
    broadcaster: 'ably',
    key: process.env.MIX_ABLY_KEY, // Certifique-se de que a chave está no .env
});
