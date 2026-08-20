import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// import react from '@vitejs/plugin-react';
import path from 'path';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    base: '',
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        
         vue({
             template: {
                 transformAssetUrls: {
                     base: null,
                     includeAbsolute: false,
                 },
             },
         }),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~bootstrap-icons': path.resolve(__dirname, 'node_modules/bootstrap-icons'),
        },
    },
    
});