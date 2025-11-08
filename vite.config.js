import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', // CSS หลักของคุณ
                'resources/js/app.js',  // JS หลักของคุณ
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': 'node_modules/bootstrap', // Alias เพื่อให้ Import ง่าย
        },
    },
});
