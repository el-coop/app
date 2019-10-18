/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue';


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
import store from "./store";

window.axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if (error.response.status === 401 && error.response.data.message === 'Unauthenticated.') {
        store.commit('auth/logout');
    }
    if(error.response.status === 419){
        router.go();
    }
    return Promise.reject(error);
});

const app = new Vue({
    el: '#app',
    router,
    store,
    metaInfo: {
        // if no subcomponents specify a metaInfo.title, this title will be used
        title: 'Loading',
        // all titles will be injected into this template
        titleTemplate: '%s | El-Coop'
    },
    data() {
        return {
            loader: false,
        }
    },
    beforeCreate() {
        this.$store.dispatch('init');
    },


    computed: {
        theme() {
            return this.$store.state.theme;
        }
    },

    beforeDestroy() {
        this.$bus.$off('theme-switch');
    }
});
