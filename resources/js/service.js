import { createApp } from 'vue';
import jQuery from 'jquery';
import 'select2'; // This will attach select2 to jQuery
import 'select2/dist/css/select2.min.css';
import Service from './components/service/Service.vue';
import Yarn from './components/service/Yarn.vue';
import CreateService from './components/service/CreateService.vue';
import VueAwesomePaginate from 'vue-awesome-paginate';

// Make jQuery global for components that need it
window.$ = window.jQuery = jQuery;

// Create and mount the Vue app
const app = createApp({
    // Root component setup if needed
});

// Register global components
app.component('service', Service);
app.component('yarn-list', Yarn);
app.component('create-service', CreateService);
app.use(VueAwesomePaginate);

// Mount the app
app.mount('#app');