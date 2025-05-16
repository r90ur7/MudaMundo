import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/theme.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '127.0.0.1', // Garante que o Vite rode em 127.0.0.1
        proxy: {
            '/broadcasting': {
                target: 'http://127.0.0.1:8000',
                changeOrigin: true,
                secure: false,
                ws: true,
            },
            '/api': 'http://127.0.0.1:8000',
        }
    }
});
