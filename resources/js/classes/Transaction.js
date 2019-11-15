import httpService from './HttpService';

class Transaction {
    static async get(page = 0, filters = '') {
        try {
            const response = await httpService.get(`/transactions?page=${page}&fitlers=${filters}`);
            if (response.status > 199 || response.status < 300) {
                const responseData = response.data;
                responseData.data = responseData.data.map((transaction) => {
                    return new Transaction(transaction);
                });
                return responseData;
            }
        } catch (e) {
        }
        return {
            status: 'error'
        }
    }

    constructor(object = {}) {
        this.status = object.id ? 'saved' : 'new';
        this.date = object.date ? new Date(object.date) : new Date();
        this.label = object.label || '';
        this.amount = parseFloat(object.amount) || 0;
        this.comment = object.comment || '';
        this.id = object.id || Date.now();
        this.dbId = object.id || null;
        this.errors = {};
    }

    async save() {
        this.status = 'uploading';
        let response;
        try {
            this.errors = {};
            let method = 'post';
            let url = '/transactions';
            if (this.dbId) {
                method = 'patch';
                url += `/${this.dbId}`;
            }

            response = await httpService[method](url, {
                date: this.date,
                label: this.label,
                amount: this.amount,
                comment: this.comment,
            });

            if (response.status > 199 && response.status < 300) {
                this.status = 'saved';
                this.dbId = response.data.id;
                return response;
            }

            this.errors = response.data.errors || [];
        } catch (error) {
            response = error.response;
        }

        this.status = 'error';
        return response;
    }

    async delete() {
        this.status = 'deleting';

        try {
            const response = await httpService.delete(`/transactions/${this.dbId}`);
            if (response.status > 199 && response.status < 300) {
                return true;
            }
        } catch (error) {
        }
        this.status = 'saved';
        return false;
    }
}

export default Transaction;
