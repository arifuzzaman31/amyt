import { createApp } from 'vue';
import AmytStock from './components/stock/AmytStock.vue';
import CustomerStock from './components/stock/CustomerStock.vue';
import CreateCustomerStock from './components/stock/CreateCustomerStock.vue';
// amyt stock list

const app = createApp()
app.component('amyt-stock', AmytStock)
app.component('customer-stock', CustomerStock)
app.component('create-customer-stock', CreateCustomerStock)
app.mount('#app');