/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue';

//Vue Addons
import VueIziToast from 'vue-izitoast';
Vue.use(VueIziToast);

require('./bootstrap');
window.Vue = Vue;

// Load vue components and libraries

require('./global/global');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import router from './router';


const app = new Vue({
	el: '#app',
	router,
	data() {
		return {
			theme: 'light',
			loader: false,
		}
	},
	created() {
		this.$bus.$on('theme-switch', (theme) => {
			this.theme = theme;
		});
	},

	beforeDestroy() {
		this.$bus.$off('theme-switch');
	}
});
