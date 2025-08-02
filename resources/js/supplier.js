import { createApp } from 'vue';
import Supplier from './components/supplier/Supplier.vue';
import PurchaseList from './components/supplier/PurchaseList.vue';
import CreatePurchase from './components/supplier/CreatePurchase.vue';
import VueAwesomePaginate from 'vue-awesome-paginate';
const app = createApp()
app.component('supplier', Supplier)
app.component('purchase-list', PurchaseList)
app.component('create-purchase', CreatePurchase)
app.use(VueAwesomePaginate);
app.mount('#app');