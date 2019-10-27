import router from '../router';
import httpService from '../classes/HttpService';

export default {
    namespaced: true,
    state: {
        user: null
    },
    getters: {
        loggedIn(state) {
            if (state.user == null) {
                state.user = localStorage.getItem('authenticated')
            }
            return state.user;
        },
    },
    mutations: {
        login(state) {
            localStorage.setItem('authenticated', 'true');
            state.user = true;
        },
        logout(state) {
            localStorage.removeItem('authenticated');
            state.user = false;
            router.push('/login');
        }
    },
    actions: {
        async login({commit}, data) {
            const response = await httpService.post('/login', data);
            if (response.status < 200 || response.status > 299) {
                throw new Error('Login Failes');
            }
            commit('login');
        }
    }
}
