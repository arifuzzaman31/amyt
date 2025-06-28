import './bootstrap';
import { createApp } from 'vue';
import Dashboard from './components/dashboard/Dashboard.vue';

const app = createApp();

app.component('view-dashboard', Dashboard);

app.mount('#app');