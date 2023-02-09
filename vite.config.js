import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

const path = require('path')

export default defineConfig({
    plugins: [
        // vue(),
        // laravel([
        //     'resource/scss/app.scss',
        //     'resources/js/app.js',
        // ]),
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                // 'sw.js',
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
            vue: 'vue/dist/vue.esm-bundler.js',
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '@': '/resources/js',

        },
        // alias: {
        //     '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        //     '@': '/resources/js',
        // }
    },
});


