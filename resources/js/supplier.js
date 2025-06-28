import { createApp } from 'vue';
import Supplier from './components/supplier/Supplier.vue';
import PurchaseList from './components/supplier/PurchaseList.vue';
import CreatePurchase from './components/supplier/CreatePurchase.vue';
import AmytStock from './components/stock/AmytStock.vue';
import CustomerStock from './components/stock/CustomerStock.vue';
// amyt stock list

const app = createApp()
app.component('supplier', Supplier)
app.component('purchase-list', PurchaseList)
app.component('create-purchase', CreatePurchase)
app.component('amyt-stock', AmytStock)
app.component('customer-stock', CustomerStock)
app.mount('#app');