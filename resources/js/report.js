import { createApp } from 'vue';
import AmytCustomerStock from './components/report/AmytCustomerStock.vue';

const app = createApp()
app.component('current-stock', AmytCustomerStock)
app.mount('#app');