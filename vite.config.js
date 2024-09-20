import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'public/sass/app.scss', 
                'public/js/app.js', 
                'public/sass/viaje.scss', 
                'public/js/viaje.js',
                'public/sass/login.scss',
                'public/js/login.js',
                'public/sass/admin.scss',                
                'public/js/admin.js',
            ],
            refresh: true,
        }),
    ],
});
