import { createApp } from 'vue';
import CustomerGroup from './components/GroupCustomer/CustomerGroup.vue';
import Customer from './components/GroupCustomer/Customer.vue';

const app = createApp()
app.component('customer-group', CustomerGroup)
app.component('view-customer', Customer)
app.mount('#app');