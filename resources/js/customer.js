import { createApp } from 'vue';
import CustomerGroup from './components/GroupCustomer/CustomerGroup.vue';
import Customer from './components/GroupCustomer/Customer.vue';

import VueAwesomePaginate from "vue-awesome-paginate";

// import the necessary css file

import "vue-awesome-paginate/dist/style.css";

// Register the package

const app = createApp()
app.component('customer-group', CustomerGroup)
app.component('view-customer', Customer)
app.use(VueAwesomePaginate);
app.mount('#app');