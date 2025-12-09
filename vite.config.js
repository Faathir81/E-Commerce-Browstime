import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        // Prevent Vite watcher from tracking heavy/vendor directories which can bloat memory on Windows
        watch: {
            ignored: [
                '**/vendor/**',
                '**/storage/**',
                '**/node_modules/**',
                '**/bootstrap/cache/**',
                '**/public/storage/**',
            ],
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [
                'resources/views/**',
                'routes/**',
                'app/Livewire/**',
                'app/Http/Livewire/**',
            ],
        }),
    ],
});
