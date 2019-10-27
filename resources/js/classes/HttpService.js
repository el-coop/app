import axios from 'axios';
import store from "../store";
import router from "../router";


function setCommonHeader(key, value) {
    axios.defaults.headers.common[key] = value;
}

class HttpService {
    constructor() {
        setCommonHeader('X-Requested-With', 'XMLHttpRequest');
        setCommonHeader('X-CSRF-TOKEN', document.head.querySelector('meta[name="csrf-token"]').content);
        axios.interceptors.response.use((response) => {
            this.setCsrfToken(response);
            return response;
        }, this.issueLogout.bind(this));
    }

    setCsrfToken(response) {
        if (response.data.csrfToken || false) {
            setCommonHeader('X-CSRF-TOKEN', response.data.csrfToken);
        }
    }

    issueLogout(error) {
        if (error.response.status === 401 && error.response.data.message === 'Unauthenticated.') {
            store.commit('auth/logout');
        }
        if (error.response.status === 419) {
            router.push('/');
        }
        return Promise.reject(error);
    }

    async get(url, headers = {}) {
        try {
            return await axios.get(url, headers);
        } catch (e) {
            return e.response;
        }
    }

    async post(url, data = {}, headers = {}, config = {}) {
        try {
            return await axios.post(url, data, {
                headers,
                ...config
            });
        } catch (e) {
            return e.response;
        }
    }
}


export default new HttpService();
