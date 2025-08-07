import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        host: 'localhost',
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
            '$': 'jquery',
        },
    },
   optimizeDeps: {
    include: ['jquery', 'select2'],
  },
  
});
