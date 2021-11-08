import { createStore } from 'vuex'
import auth from "./auth";
import User from "./user";


const store = createStore({
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
