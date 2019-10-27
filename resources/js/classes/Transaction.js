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
        this.date = new Date(object.date) || (new Date()).toISOString().split('T')[0];
        this.label = object.label || '';
        this.amount = object.amount || 0;
        this.comment = object.comment || '';
        this.id = object.id || Date.now()
    }

    async save() {
        this.status = 'uploading';
        try {
            const response = await httpService.post('/transactions', {
                date: this.date,
                label: this.label,
                amount: this.amount,
                comment: this.comment,
            });

            if (response.status > 199 && response.status < 300) {
                this.status = 'saved';
                this.id = response.data.id;
                return true;
            }

            this.errors = response.data;
        } catch (e) {
        }

        this.status = 'error';
        return false;
    }
}

export default Transaction;

