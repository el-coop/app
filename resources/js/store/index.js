import Vue from 'vue';
import Vuex from 'vuex';
import auth from "./auth";
import User from "./user";

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        auth,
        User
    },

    state: {
        theme: 'light'
    },

    mutations: {
        switchTheme(state, theme) {
            state.theme = theme;
            localStorage.setItem('theme', theme);
        }
    },

    actions: {
        init({commit}) {
            commit('switchTheme', localStorage.getItem('theme') || 'light');
        },
    }
});

export default store;
