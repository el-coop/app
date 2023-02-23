import httpService from '../classes/HttpService';
import FormException from '../classes/FormException';

export default {
    namespaced: true,
    state: {
        user: null
    },
    mutations: {
        invoiceNumberChange(state, nextInvoice) {
            if (state.user && state.user.invoiceSettings) {
                state.user.invoiceSettings.nextInvoice = nextInvoice;
            }
        }
    },
    actions: {
        async scheduledActions({state}) {
            if (state.user) {
                return state.user.scheduledActions;
            }
            const response = await httpService.get('/user');
            if (response.status < 200 || response.status > 299) {
                throw new Error('Error loading user data');
            }

            state.user = response.data;
            return state.user.scheduledActions;
        },

        async saveAction({state}, data) {
            const response = await httpService.patch('/user/scheduledActions', data);
            if (response.status < 200 || response.status > 299) {
                throw new FormException('Save error', response.data.errors);
            }
            state.user.scheduledActions[data.action] = data.frequency;
        },

        async getInvoiceSettings({state}) {
            if (state.user) {
                return state.user.invoiceSettings;
            }
            const response = await httpService.get('/user');
            if (response.status < 200 || response.status > 299) {
                throw new Error('Error loading user data');
            }

            state.user = response.data;
            return state.user.invoiceSettings;

        },

        async saveInvoiceSettings({state}, data) {
            const response = await httpService.patch('/user/invoiceSettings', data);
            if (response.status < 200 || response.status > 299) {
                throw new FormException('Save error', response.data.errors);
            }
            state.user.invoiceSettings = data;
        }
    }
}
