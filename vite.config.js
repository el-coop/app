import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// import react from '@vitejs/plugin-react';
import vue from '@vitejs/plugin-vue';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    resolve: {
        alias: {
            '@': '/resources/js',
        }
    },
    plugins: [
        laravel({
            input:[
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        VitePWA({
            strategies: "injectManifest",
            injectRegister: null,
            srcDir: "resources/js/serviceWorker",
            filename: "serviceWorker.js",
            manifest: {
                "name": "El-Coop App",
                "short_name": "El-Coop App",
                "icons": [
                    {
                        "src": "images/icons/icon-72x72.png",
                        "sizes": "72x72",
                        "type": "image/png"
                    },
                    {
                        "src": "images/icons/icon-96x96.png",
                        "sizes": "96x96",
                        "type": "image/png"
                    },
                    {
                        "src": "images/icons/icon-128x128.png",
                        "sizes": "128x128",
                        "type": "image/png"
                    },
                    {
                        "src": "images/icons/icon-144x144.png",
                        "sizes": "144x144",
                        "type": "image/png"
                    },
                    {
                        "src": "images/icons/icon-152x152.png",
                        "sizes": "152x152",
                        "type": "image/png"
                    },
                    {
                        "src": "images/icons/icon-192x192.png",
                        "sizes": "192x192",
                        "type": "image/png"
                    },
                    {
                        "src": "images/icons/icon-384x384.png",
                        "sizes": "384x384",
                        "type": "image/png"
                    },
                    {
                        "src": "images/icons/icon-512x512.png",
                        "sizes": "512x512",
                        "type": "image/png"
                    }
                ],
                "start_url": "/",
                "display": "standalone",
                "orientation": "portrait",
                "background_color": "#363636",
                "theme_color": "#209CEE"
            },
            manifestFilename: "pwa-manifest.json",
        }),
    ],
});
