// Global libraries and setup
import VueRouter from 'vue-router';
import { library } from '@fortawesome/fontawesome-svg-core';
import {
    faFileInvoiceDollar,
    faSignOutAlt,
    faEdit,
    faExclamation,
    faTrash,
    faSearch,
    faIdCard,
    faCaretDown,
    faList,
    faCheck,
    faEye, faDatabase, faUpload, faTimesCircle, faFile, faMoneyCheckAlt
} from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import VueIziToast from 'vue-izitoast';
import VueMeta from 'vue-meta';

Vue.use(VueIziToast);
Vue.use(VueMeta);
Vue.use(VueRouter);
Vue.prototype.$bus = new Vue();
library.add(faCheck,faFileInvoiceDollar, faSignOutAlt, faEdit, faExclamation, faTrash, faSearch, faIdCard, faCaretDown, faList, faEye, faDatabase, faUpload, faTimesCircle, faFile, faMoneyCheckAlt);

Vue.component('FontAwesomeIcon', FontAwesomeIcon);

// Components
import Navbar from './Navbar';

Vue.component('Navbar', Navbar);
