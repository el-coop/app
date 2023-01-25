/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import {createApp} from 'vue'
import {createMetaManager, plugin as vueMetaPlugin } from 'vue-meta'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import VueIzitoast from './classes/VueIzitoast';
import Navbar from './global/Navbar.vue';


import './bootstrap';

// Load vue components and libraries

import './global/global';


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import router from './router';
import store from "./store";
import Root from "./components/Root.vue";

const app = createApp(Root)
    .use(router)
    .use(createMetaManager())
    .use(vueMetaPlugin)
    .use(store)
    .use(VueIzitoast)
    .component('FontAwesomeIcon', FontAwesomeIcon)
    .component('Navbar', Navbar);

router.app = app;
console.log('update1');
router.isReady().then(() => app.mount('#app'));
