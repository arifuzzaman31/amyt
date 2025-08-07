import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        host: 'localhost', // This tells Vite to listen on all local network addresses
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/customer.js',
                'resources/js/expense.js',
                'resources/js/supplier.js',
                'resources/js/service.js',
                'resources/js/stock.js',
                'resources/js/report.js'
            ],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            'vue': 'vue/dist/vue.esm-bundler.js',
            // Add a simple alias for the $ symbol
            '$': 'jquery',
        },
    },
    optimizeDeps: {
        esbuildOptions: {
            plugins: [
                // This plugin ensures that global $ and jQuery are defined
                // for CJS modules like Select2
                {
                    name: 'jquery-global-shim',
                    setup(build) {
                        build.onLoad({ filter: /jquery\.js/ }, async (args) => {
                            const contents = `
                                import 'jquery';
                                window.$ = window.jQuery;
                                export default window.$;
                            `;
                            return { contents, loader: 'js' };
                        });
                    },
                },
            ],
        },
    },
});