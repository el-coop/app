import Model from "./Model";

export default class Transaction extends Model{
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

    static get endpoint(){
        return '/transactions';
    }
}
