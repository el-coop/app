import axios from 'axios';
import store from "../store";
import HttpResponse from "./HttpResponse";
import HttpException from "./Exceptions/HttpException";

let csrf = document.head.querySelector('meta[name="csrf-token"]').content;

function getHeaders(headers) {
    return {
        'X-XSRF-TOKEN': csrf,
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        ...headers
    }
}

async function processResponse(response) {
    let data = '';
    if (response.statusText !== 'No Content') {
        data = await response.json();
    }

    return new HttpResponse(response.status, response.headers, data);
}


class HttpService {
    setCsrfToken(response) {
        if (response.data.csrfToken || false) {
            csrf = response.data.csrfToken;
        }
    }

    issueLogout(response) {
        if (response.status === 401 && response.data.message === 'Unauthenticated.') {
            store.commit('auth/logout');
        }
    }

    async handleResponse(response, method, repeat, url, data, headers, config = {}) {
        const processedResponse = await processResponse(response);

        if (!response.ok) {
            this.issueLogout(processedResponse);
            if (repeat && response.status === 419) {
                return await this.repeatWithCsrf(method, url, headers, data, config)
            }
            throw new HttpException(`HTTP error! status: ${response.status}`, processedResponse);
        }

        return processedResponse;
    }


    async get(url, headers = {}) {
        try {
            const response = await fetch(url, {
                headers: getHeaders(headers)
            });
            return await this.handleResponse(response, 'get', false, url, {}, headers);
        } catch (error) {
            return error.response || new HttpResponse(500, {}, {error});
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

    async delete(url, headers = {}, repeat = true) {
        try {
            return await axios.delete(url, {
                headers,
            });
        } catch (error) {
            if (error.response && error.response.data.message === 'CSRF token mismatch.' && repeat) {
                return await this.repeatWithCsrf('delete', url, headers);
            }
            return error.response;
        }
    }

    async repeatWithCsrf(method, url, headers, data = {}, config = {}) {
        const response = await this.get('csrf');
        csrf = response.data.token;

        if (method === "delete") {
            return await this.delete(url, headers, false);
        }
        return await this[method](url, data, headers, config, false);
    }

}


export default new HttpService();
