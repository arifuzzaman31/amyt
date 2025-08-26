import { createApp } from 'vue';
import VueAwesomePaginate from 'vue-awesome-paginate';
import Service from './components/service/Service.vue';
import Yarn from './components/service/Yarn.vue';
import CreateService from './components/service/CreateService.vue';
import Quotation from './components/service/Quotation.vue';

// Vue Application setup
// ----------------------------------------------------
const app = createApp({});

// Register plugins
app.use(VueAwesomePaginate);

// Register global components for use throughout the app
app.component('service', Service);
app.component('yarn-list', Yarn);
app.component('create-service', CreateService);
app.component('quotation-list', Quotation);
// Mount the app to the DOM
app.mount('#app');