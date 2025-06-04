import { createApp } from 'vue';
import ServiceApp from './ServiceApp.vue'; // Import the new root component
import Service from './components/service/Service.vue';
import Yarn from './components/service/Yarn.vue';
import VueAwesomePaginate from 'vue-awesome-paginate';

const app = createApp(ServiceApp); // Use ServiceApp as the root component

// Global components will be available within ServiceApp's template or slot
app.component('service', Service);
app.component('yarn-list', Yarn);

app.use(VueAwesomePaginate).mount('#app');