import httpService from './HttpService';

export default class Transaction {
    static fields() {
        return [{
            name: 'date',
            type: 'date',
            label: 'Date',
            format: 'dd/mm/yyyy',
        }, {
            name: 'entity',
            label: 'To/From',
            class: 'select--fullwidth',
            component: 'SelectField',
            type: "number"

        }, {
            name: 'reason',
            label: 'Reason',
        }, {
            name: 'currency',
            label: 'Currency',
            width: 1 / 2,
            class: 'select--fullwidth',
            component: 'SelectField',
            options: {
                '₪': '₪',
                '$': '$',
                '€': '€'
            }
        }, {
            name: 'rate',
            label: 'Rate (leave empty to auto calculate)',
            type: 'number',
            width: 1 / 2
        }, {
            name: 'amount',
            label: 'Amount',
            type: 'number'
        }, {
            name: 'comment',
            label: 'Comment',
            component: 'TextareaField'
        }]
    }

    constructor(object = {}) {
        this.status = object.id ? 'saved' : 'new';
        this.id = object.id || Date.now();
        this.dbId = object.id || null;
        this.errors = {};
        Transaction.fields().forEach((field) => {
            const propertyName = field.name;
            if (object[propertyName]) {
                this[propertyName] = object[propertyName];
                if (field.type === 'date') {
                    this[propertyName] = new Date(this[propertyName]);
                } else if (field.type === 'number') {
                    this[propertyName] = parseFloat(this[propertyName]);
                }
            } else {
                switch (propertyName) {
                    case 'date':
                        this[propertyName] = new Date();
                        break;
                    case 'amount':
                        this[propertyName] = 0;
                        break;
                    default:
                        this[propertyName] = '';
                }
            }
        });
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

            response = await httpService[method](url, this.postData());

            if (response.status > 199 && response.status < 300) {
                this.status = 'saved';
                this.dbId = response.data.id;
                this.rate = response.data.rate;
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

    postData() {
        const result = {};
        Transaction.fields().forEach((field) => {
            const propertyName = field.name;
            result[propertyName] = this[propertyName];
        });
        return result;
    }

}
