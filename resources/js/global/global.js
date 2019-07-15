// Global libraries and setup
import VueRouter from 'vue-router';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faFileInvoiceDollar } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

Vue.use(VueRouter);
Vue.prototype.$bus = new Vue();
library.add(faFileInvoiceDollar);

Vue.component('FontAwesomeIcon', FontAwesomeIcon);
// Components
import Navbar from './Navbar';

Vue.component('Navbar', Navbar);
