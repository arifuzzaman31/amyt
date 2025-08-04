import { createApp } from 'vue';
import $ from 'jquery';
import 'select2';
import 'select2/dist/css/select2.css';
import Select2 from 'vue3-select2-component';
import AmytStock from './components/stock/AmytStock.vue';
import CustomerStock from './components/stock/CustomerStock.vue';
import CreateCustomerStock from './components/stock/CreateCustomerStock.vue';
import VueAwesomePaginate from 'vue-awesome-paginate';
// amyt stock list
window.$ = window.jQuery = $;
const app = createApp()
app.component('amyt-stock', AmytStock)
app.component('customer-stock', CustomerStock)
app.component('create-customer-stock', CreateCustomerStock)
app.component('Select2', Select2);
app.use(VueAwesomePaginate);
app.mount('#app');