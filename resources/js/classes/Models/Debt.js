import Model from "./Model";

let debtList = null;

export default class Debt extends Model {
    static fields() {
        return [{
            name: 'date',
            type: 'date',
            label: 'Date',
            format: 'dd/mm/yyyy',
        }, {
            name: 'entity',
            label: 'From',
            class: 'select--fullwidth',
            component: 'SelectField',
            type: 'number',
        }, {
            name: 'project',
            label: 'Project',
            class: 'select--fullwidth',
            component: 'SelectField',
            options(field, value, vm) {
                const entity = value['entity'];
                const result = {};
                if (entity && field.groupedProjects[entity]) {
                    result[''] = '';
                    field.groupedProjects[entity].forEach((project) => {
                        result[`${project.id} `] = project.name;
                    });
                }
                return result;
            },
            projects: {},
        }, {
            name: 'type',
            label: 'Type',
            class: 'select--fullwidth',
            component: 'SelectField',
            width: 1 / 2,
            options: {
                fixed: 'Fixed Payment',
                hourly: 'Hourly Payment',
            }
        }, {
            name: 'amount',
            label: 'Amount',
            help: 'Hours for hourly, Money for fixed',
            width: 1 / 2,
            type: 'number'
        }, {
            name: 'rate',
            label: 'Hourly Rate',
            type: 'number',
            width: 1 / 2,
            show(value) {
                return value.type === 'hourly';
            }
        }, {
            name: 'currency',
            label: 'Currency',
            class: 'select--fullwidth',
            width: 1 / 2,
            component: 'SelectField',
            options: {
                '₪': '₪',
                '$': '$',
                '€': '€'
            }
        }, {
            name: 'invoiced',
            label: 'Billed',
            component: 'SelectField',
            class: 'select--fullwidth',
            type: 'number',
            options: {
                0: 'No', 1: 'Yes'
            }
        }, {
            name: 'comment',
            label: 'Comment',
            component: 'TextareaField'
        }, {
            name: 'nisAmount',
            type: "number",
            show: false
        }]
    }

    static get endpoint() {
        return 'debts';
    }

    static async list() {
        if (!debtList) {
            debtList = await Debt.get();
        }

        return debtList || [];
    }

    static updateCallback(debt, response) {
        debt.nisAmount = response.data.nisAmount;
    }
}
