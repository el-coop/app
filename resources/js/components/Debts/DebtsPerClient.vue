<template>
    <div class="card">
        <div class="card__header">
            <h5 class="title is-size-6">Debts per client</h5>
        </div>
        <table class="table">
            <tbody>
            <tr>
                <th class="table__cell table__cell--header  table__cell--important">Client</th>
                <th class="table__cell">Dollar</th>
                <th class="table__cell">Euro</th>
                <th class="table__cell">NIS</th>
                <th class="table__cell table__cell--header table__cell--right table__cell--important">Total</th>
            </tr>
            <tr v-for="(debt, client) in groupedDebts" class="table__row">
                <td class="table__cell  table__cell--important" v-text="client"/>
                <td class="table__cell" v-for="currency in ['$','€','₪']"
                    v-text="debt[currency] ? `${currency}${debt[currency]}` : ''"/>

                <td class="table__cell  table__cell--right table__cell--important">
                    <span class="is-nis" v-text="debt.nisAmount.toFixed(2)"/>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    name: 'DebtsPerClient',
    props: {
        debts: {
            required: true,
            type: Array
        }
    },


    computed: {
        groupedDebts() {
            const debts = {};
            this.debts.forEach((debt) => {
                if (!debts[debt.entityName]) {
                    debts[debt.entityName] = {};
                }
                if (!debts[debt.entityName][debt.currency]) {
                    debts[debt.entityName][debt.currency] = 0;
                }
                if (!debts[debt.entityName]['nisAmount']) {
                    debts[debt.entityName]['nisAmount'] = 0;
                }
                debts[debt.entityName][debt.currency] += debt.amount;
                debts[debt.entityName]['nisAmount'] += debt.nisAmount;
            });

            return debts;
        }
    },
}
</script>
