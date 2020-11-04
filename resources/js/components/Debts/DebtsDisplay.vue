<template>
    <div class="card">
        <div class="card__header">
            <h5 class="title is-size-6">Total outstanding debts</h5>
        </div>
        <div class="title is-size-1 has-text-centered"><span class="is-size-5 is-nis"/><span
            v-text="debtsTotal.toFixed(2)"/>
        </div>
        <table class="table">
            <tbody>
            <tr class="table__row">
                <td class="table__cell">In euro</td>
                <td class="table__cell table__cell--right" v-text="`€${euroTotal.toFixed(2)}`"/>
            </tr>
            <tr class="table__row">
                <td class="table__cell">In USD</td>
                <td class="table__cell table__cell--right" v-text="`$${usdTotal.toFixed(2)}`"/>
            </tr>
            <tr class="table__row">
                <td class="table__cell">In nis</td>
                <td class="table__cell is-nis table__cell--right" v-text="`${nisTotal.toFixed(2)}`"/>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    name: 'DebtsDisplay',
    props: {
        debts: {
            required: true,
            type: Array
        }
    },

    methods: {
        calcTotal(debts, inNis = false) {
            return debts.reduce((total, debt) => {
                if (!debt.invoiced) {
                    total += (inNis ? debt.nisAmount : debt.totalAmount);
                }

                return total;
            }, 0);
        }
    },

    computed: {
        debtsTotal() {
            return this.calcTotal(this.debts, true);
        },

        euroTotal() {
            const debts = this.debts.filter((debt) => {
                return debt.currency === '€';
            });

            return this.calcTotal(debts);
        },
        usdTotal() {
            const debts = this.debts.filter((debt) => {
                return debt.currency === '$';
            });

            return this.calcTotal(debts);
        },

        nisTotal() {
            const debts = this.debts.filter((debt) => {
                return debt.currency === '₪';
            });

            return this.calcTotal(debts);
        }
    },
}
</script>
