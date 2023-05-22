import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from "@vitejs/plugin-vue";
import viteCompression from "vite-plugin-compression";

export default defineConfig({build: {
        watch: {
            // https://rollupjs.org/configuration-options/#watch
        },
    },
    plugins: [
        viteCompression({
            algorithm: 'gzip',
            ext: '.gz',
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        laravel({
            input: ['/resources/js/app.js', '/resources/js/main.js', '/resources/js/login.js'],
            refresh: true,
        }),
    ],
});
