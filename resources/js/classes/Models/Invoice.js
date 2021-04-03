import httpService from "../HttpService";

const groupItems = Symbol('groupItems');
const calculateLines = Symbol('groupItems');

export default class Invoice {

    constructor(items) {
        this.currency = null;
        this.items = this[calculateLines](items);
        this.markBilled = false;
        this.from = null;
        this.to = null;
        this.invoiceNumber = null;
        this.date = new Date();
        this.dueDate = new Date();
        this.notes = '';
        this.errors = {};
    }

    async generateSmartechEmail() {
        const response = await this.generate(true);
        if (response.status > 199 && response.status < 300) {
            return true;
        }

        return false;
    }

    async generate(generateEmail = false) {
        let url = '/invoice/generate';
        let config = {
            responseType: 'blob'
        };
        if (generateEmail) {
            url = 'invoice/smartechEmail';
            config = {};
        }

        let response;
        this.errors = {};
        try {
            response = await httpService.post(url, {
                items: this.items,
                markBilled: this.markBilled,
                currency: this.currency,
                from: this.from,
                to: this.to,
                invoiceNumber: this.invoiceNumber,
                date: this.date,
                dueDate: this.dueDate,
                notes: this.notes
            }, {}, config);

            if (response.status > 199 && response.status < 300) {
                return response;
            }

            this.errors = response.data.errors || {};
        } catch (error) {
            response = error.response;
        }

        return response;
    }

    [calculateLines](items) {
        const lines = [];
        items = this[groupItems](items);
        for (let item in items) {
            const line = {...items[item]};
            if (line.type == 'fixed') {
                line.amount = 1;
                line.rate = item.amount;
            }
            lines.push(line);
        }
        return lines;
    }

    [groupItems](items) {
        const groupedItems = {};
        items.forEach((item) => {
            if (!this.currency) {
                this.currency = item.currency
            }
            let key = `${item.comment}_${item.currency}_${item.type}`;
            if (item.type === 'hourly') {
                key += `_${item.rate}`;
            }

            if (!groupedItems[key]) {
                groupedItems[key] = {
                    comment: item.comment,
                    amount: item.type === 'hourly' ? parseFloat(item.amount) : 1,
                    rate: item.type === 'hourly' ? item.rate : parseFloat(item.amount),
                    debts: [item.id],
                    currency: item.currency
                };
            } else {
                if (item.type === 'hourly') {
                    groupedItems[key].amount += parseFloat(item.amount);
                } else {
                    groupedItems[key].rate += parseFloat(item.amount);
                }
                groupedItems[key].debts.push(item.id);
            }
        });
        return groupedItems;
    }
}


