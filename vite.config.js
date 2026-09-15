import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/style.css',      // Harus ada
                'resources/css/style-dark.css', // Harus ada
                'resources/css/auth.css',
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/shift.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: 'localhost',
        cors: true,
        headers: {
            'Access-Control-Allow-Origin': '*',
        },
    },
});