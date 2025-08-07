import { createApp } from 'vue';
import Supplier from './components/supplier/Supplier.vue';
import PurchaseList from './components/supplier/PurchaseList.vue';
import CreatePurchase from './components/supplier/CreatePurchase.vue';
import $ from 'jquery';
import 'select2';
import 'select2/dist/css/select2.css';
import VueAwesomePaginate from 'vue-awesome-paginate';
import Select2 from 'vue3-select2-component';

// Expose jQuery globally for Select2 and other plugins
window.$ = window.jQuery = $;
const app = createApp()
app.component('supplier', Supplier)
app.component('purchase-list', PurchaseList)
app.component('create-purchase', CreatePurchase)
app.component('Select2', Select2);
app.use(VueAwesomePaginate);
app.mount('#app');