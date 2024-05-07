import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import path from "path";
import NodeGlobalsPolyfillPlugin from "@esbuild-plugins/node-globals-polyfill";
import nodePolyfills from 'vite-plugin-node-stdlib-browser';

export default defineConfig({
    optimizeDeps: {
        esbuildOptions: {
            // Node.js global to browser globalThis
            define: {
                global: 'globalThis'
            },
            // Enable esbuild polyfill plugins
            plugins: [
                NodeGlobalsPolyfillPlugin({
                    buffer: false
                })
            ]
        }
    },
    plugins: [
        react(),
        nodePolyfills(),
        laravel({
            input: [
                // CSS
                'resources/sass/app.scss',
                'resources/sass/admin.scss',
                'resources/sass/event.scss',
                'resources/sass/event-job.scss',
                'resources/sass/event-auth.scss',
                'resources/sass/game.scss',
                // JS
                'resources/js/claim.js',
                'resources/js/claim-session.js',
                'resources/js/mint.js',
                // 'resources/js/mint_booth.js',
                // 'resources/js/mint_session.js',
                // 'resources/js/admin/adminapp.js',
                'resources/js/admin.js',
                'resources/js/zklogin.js',
                'resources/js/event.js',
                'resources/js/game.js',
                'resources/js/profile-nft.js',
                //'resources/css/mail.css',
                'resources/js/connect_suit_session.jsx',
                'resources/js/connect_suit_create_event.jsx',
                'resources/js/session.jsx',
                'resources/js/formNft.jsx',
                'resources/js/connect-wallet.jsx',
                'resources/js/deposit.jsx',
                'resources/js/deposit-wallet.jsx',
                'resources/js/connect_suit_booth.jsx',
                'resources/js/ModalWallet.jsx',
                'resources/js/connect-wallet-admin.jsx',
                'resources/js/admin/pages/pass-addon.init.js',
                'resources/js/app/layout.tsx',
                'resources/js/mintNftSession.jsx',
                'resources/js/app/provider.jsx',
                'resources/js/views/solana.jsx',
                'resources/js/testReactjs.jsx',
                'resources/js/statusSession.jsx',
                'resources/js/addClient.jsx',
                'resources/js/statusEvent.jsx'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '@': path.resolve(__dirname, 'resources/assets'),
            '@nm': path.resolve(__dirname, 'node_modules'),
        }
    },
});
