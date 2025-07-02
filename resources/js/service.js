import { createApp } from 'vue';
import Service from './components/service/Service.vue';
import Yarn from './components/service/Yarn.vue';
import CreateService from './components/service/CreateService.vue';
import VueAwesomePaginate from 'vue-awesome-paginate';

const app = createApp(); // Use ServiceApp as the root component

// Global components will be available within ServiceApp's template or slot
app.component('service', Service);
app.component('yarn-list', Yarn);
app.component('create-service', CreateService);

app.use(VueAwesomePaginate).mount('#app');