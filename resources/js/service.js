import { createApp } from 'vue';

// Third-party libraries
// ----------------------------------------------------
import $ from 'jquery';
import 'select2';
import 'select2/dist/css/select2.css';
import VueAwesomePaginate from 'vue-awesome-paginate';
import Select2 from 'vue3-select2-component';

// Expose jQuery globally for Select2 and other plugins
window.$ = window.jQuery = $;

// Local components
// ----------------------------------------------------
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
app.component('Select2', Select2);

// Mount the app to the DOM
app.mount('#app');