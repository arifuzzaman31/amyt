// supplier.js
import $ from 'jquery';
import 'select2';
import 'select2/dist/css/select2.css';
if (!$.fn.select2) {
    $.fn.select2 = window.select2 || window.$.select2 || undefined;
}
// Expose jQuery globally
window.$ = window.jQuery = $;
globalThis.$ = globalThis.jQuery = $;

// Now import other Vue components
import { createApp } from 'vue';
import Supplier from './components/supplier/Supplier.vue';
import PurchaseList from './components/supplier/PurchaseList.vue';
import CreatePurchase from './components/supplier/CreatePurchase.vue';

import VueAwesomePaginate from 'vue-awesome-paginate';
import Select2 from 'vue3-select2-component';

const app = createApp()
app.component('supplier', Supplier)
app.component('purchase-list', PurchaseList)
app.component('create-purchase', CreatePurchase)
app.component('Select2', Select2);
app.use(VueAwesomePaginate);
app.mount('#app');
