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
        return Promise.reject(error);
    }

    async get(url, headers = {}) {
        try {
            return await axios.get(url, headers);
        } catch (error) {
            return error.response;
        }
    }

    async post(url, data = {}, headers = {}, config = {}, repeat = true) {
        try {
            return await axios.post(url, data, {
                headers,
                ...config
            });
        } catch (error) {
            if (error.response && error.response.data.message === 'CSRF token mismatch.' && repeat) {
                return await this.repeatWithCsrf('post', url, headers, data, config);
            }
            return error.response;
        }
    }

    async patch(url, data = {}, headers = {}, config = {}, repeat = true) {
        try {
            return await axios.patch(url, data, {
                headers,
                ...config
            });
        } catch (error) {
            if (error.response && error.response.data.message === 'CSRF token mismatch.' && repeat) {
                return await this.repeatWithCsrf('patch', url, headers, data, config);
            }
            return error.response;
        }
    }

    async repeatWithCsrf(method, url, headers, data = {}, config = {}) {
        const response = await this.get('csrf');
        setCommonHeader('X-CSRF-TOKEN', response.data.token);

        if (method === "delete") {
            return await this.delete(url, headers, false);
        }
        return await this[method](url, data, headers, config, false);
    }

}


export default new HttpService();
