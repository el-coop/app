// Global libraries and setup
import VueRouter from 'vue-router';
import { library } from '@fortawesome/fontawesome-svg-core';
import {
    faFileInvoiceDollar,
    faSignOutAlt,
    faEdit,
    faExclamation,
    faTrash,
    faSearch
} from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import VueIziToast from 'vue-izitoast';
import VueMeta from 'vue-meta';

Vue.use(VueIziToast);
Vue.use(VueMeta);
Vue.use(VueRouter);
Vue.prototype.$bus = new Vue();
library.add(faFileInvoiceDollar, faSignOutAlt, faEdit, faExclamation, faTrash, faSearch);

Vue.component('FontAwesomeIcon', FontAwesomeIcon);

// Components
import Navbar from './Navbar';

Vue.component('Navbar', Navbar);
