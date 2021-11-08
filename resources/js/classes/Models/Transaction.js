import Model from "./Model";

export default class Transaction extends Model {
    static updateCallback(transaction, response) {
        transaction.files = [];
        transaction.attachments = [];
        response.data.attachments.forEach((attachment) => {
            transaction.attachments.push({
                id: attachment.id,
                name: attachment.name,
                checked: true
            });
        });
        transaction.rate = response.data.rate;
    }

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
        }, {
            name: 'attachments',
            type: 'array',
            label: 'Current files',
            component: 'FileArrayField'
        }, {
            name: 'files',
            type: 'array',
            label: 'Attach files',
            component: 'MultiFileField'
        }]
    }

    static get endpoint() {
        return '/transactions';
    }
}
