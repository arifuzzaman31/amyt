import './bootstrap';
import { createApp } from 'vue';
import Dashboard from './components/dashboard/Dashboard.vue';
import Attribute from './components/attribute/Attribute.vue';
const app = createApp();

app.component('view-dashboard', Dashboard);
app.component('view-attribute', Attribute);

app.mount('#app');