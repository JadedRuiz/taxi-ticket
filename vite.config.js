import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'public/sass/app.scss', 
                'public/sass/viaje.scss', 
                'public/sass/login.scss',
                'public/sass/admin.scss', 
                'public/sass/vehiculo.scss', 
                'public/js/app.js', 
                'public/js/viaje.js', 
                'public/js/login.js',              
                'public/js/admin.js',               
                'public/js/destino.js',
                'public/js/vehiculo.js',
                'public/js/operador.js',
            ],
            refresh: true,
        }),
    ],
});
