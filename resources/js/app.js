// import 'jquery';
import './bootstrap';
import '../sass/app.scss';
import '../css/app.css';
import "@vueform/multiselect/themes/default.css";

// Import all of Bootstrap's JS
import * as bootstrap from 'bootstrap'

// import $ from "jquery";

import { createApp } from 'vue';

import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;

import Router from './router';

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);
import Dashboard from "./components/Dashboard.vue";
app.component('dashboard', Dashboard);


// Object.entries(import.meta.globEager('./**/*.vue')).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* import specific icons */
import { faUserSecret, faPencil, faTrashCan, faPlus, faMinus } from '@fortawesome/free-solid-svg-icons'

/* add icons to the library */
library.add(faUserSecret, faPencil, faTrashCan, faPlus, faMinus)

import Multiselect from '@vueform/multiselect'

import { ref, computed } from 'vue';
import Paginate from "./components/Paginate.vue";

import dayjs from 'dayjs'
app.config.globalProperties.$dayjs = dayjs


app.use(Router);
app.component('font-awesome-icon', FontAwesomeIcon);
app.component('Multiselect', Multiselect);
app.component('paginate', Paginate);
app.mount('#app');
