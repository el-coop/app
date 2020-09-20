import Model from "./Model";

let debtList = null;

export default class Debt extends Model {
    static fields() {
        return [{
            name: 'name',
            label: 'Entity Name',
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
}
