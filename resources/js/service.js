import { createApp } from 'vue';
import Service from './components/service/Service.vue';
import Yarn from './components/service/Yarn.vue';

const app = createApp()
app.component('service', Service)
app.component('yarn-list', Yarn)
app.mount('#app');