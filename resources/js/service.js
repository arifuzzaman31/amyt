import { createApp } from 'vue';
import Service from './components/service/Service.vue';

const app = createApp()
app.component('service', Service)
app.mount('#app');