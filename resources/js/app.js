import './bootstrap';
import '../sass/app.scss';
import '../css/app.css';
import "@vueform/multiselect/themes/default.css";

// import 'jquery';
// import $ from "jquery";

import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;

// Object.entries(import.meta.globEager('./**/*.vue')).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* import specific icons */
import { faUserSecret, faPencil, faTrashCan, faPlus, faMinus, faEye } from '@fortawesome/free-solid-svg-icons'

/* add icons to the library */
library.add(faUserSecret, faPencil, faTrashCan, faPlus, faMinus, faEye)

import Multiselect from '@vueform/multiselect'
import Paginate from "./components/Paginate.vue";
import Router from './router';
import dayjs from 'dayjs'
import { ref, computed } from 'vue';


const mixin = {
    data() {return {
        errPage: false,
        successPage: false,
        errs: null,
    }},
    mounted() {
        this.errPage = false;
        this.successPage = false;
        this.errs = null;
    },
    methods: {
        errorsMessage(err) {
            console.log(err.response)
            if (err.response.data.errors) {
                this.errs = {};
                this.errs = err.response.data.errors
            } else {
                switch (err.response.status) {
                    case 500:
                    case 404:
                    case 401:
                        console.log(err.response.status)
                        this.errPage = true;
                        this.errs = err.response;
                        break;
                    case 422:
                        this.errs = err.response.data.message;
                        break;
                }
            }
        },
    }
}


import { createApp } from 'vue';
const app = createApp({

    mounted() {

    },
    methods: {
    }
});

// import ExampleComponent from './components/ExampleComponent.vue';
// app.component('example-component', ExampleComponent);
import Dashboard from "./components/Dashboard.vue";
app.component('dashboard', Dashboard);
import ErrorPage from "./components/ErrorPage/ErrorPage.vue";
app.component('error-page', ErrorPage)
import ErrorsValidation from "./components/ErrorPage/ErrorsValidation.vue";
app.component('errors-validation', ErrorsValidation);

app.config.globalProperties.$dayjs = dayjs
app.use(Router);
app.component('font-awesome-icon', FontAwesomeIcon);
app.component('Multiselect', Multiselect);
app.component('paginate', Paginate);
app.mixin(mixin);

app.mount('#app');
