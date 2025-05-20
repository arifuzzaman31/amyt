import { createApp } from 'vue';
import Supplier from './components/supplier/Supplier.vue';
// import Supplier from './components/supplier/Purchase.vue';

const app = createApp()
app.component('supplier', Supplier)
// app.component('purchase', Purchase)
app.mount('#app');