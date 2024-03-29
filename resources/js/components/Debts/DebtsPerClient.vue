<template>
    <div class="card">
        <div class="card__header">
            <h5 class="title is-size-6">Debts per client</h5>
            <button class="button is-success" @click="invoice()">New Invoice</button>
        </div>
        <table class="table">
            <tbody>
            <tr>
                <th class="table__cell table__cell--header table__cell--narrow table__cell--important">Action</th>
                <th class="table__cell table__cell--header  table__cell--important">Client</th>
                <td class="table__cell table__cell--narrow table__cell--right"
                    v-for="currency in ['Dollar','Euro','NIS']" v-text="currency"/>
                <th class="table__cell table__cell--header table__cell--right table__cell--important table__cell--narrow">
                    Total
                </th>
            </tr>
            <tr v-for="(debt, client) in groupedDebts" class="table__row">
                <td class="table__cell table__cell--important table__cell--narrow">
                    <button class="button is-success" @click="invoice(groupedDebts[client])">
                        <FontAwesomeIcon icon="file-invoice-dollar" class="button__icon"/>
                        Invoice
                    </button>
                </td>
                <td class="table__cell table__cell--important" v-text="client"/>
                <td class="table__cell table__cell--narrow  table__cell--right" v-for="currency in ['$','€','₪']"
                    v-text="debt[currency] ? `${currency}${debt[currency]}` : ''"/>

                <td class="table__cell table__cell--narrow  table__cell--right table__cell--important">
                    <span class="is-nis" v-text="debt.nisAmount.toFixed(2)"/>
                </td>
            </tr>
            </tbody>
        </table>
        <InvoiceModal :addresses="addresses" :debt-list="invoicingDebts" @close-invoicing="invoicingDebts = null"
                      @markBilled="markBilled"/>
    </div>
</template>
<script>
import InvoiceModal from "./InvoiceModal.vue";
import InteractsWithObjects from "../../mixins/InteractsWithObjects";

export default {
    name: 'DebtsPerClient',
    components: {InvoiceModal},
    mixins: [InteractsWithObjects],
    props: {
        debts: {
            required: true,
            type: Array
        },
        entities: {
            required: true,
            type: Array
        }
    },

    data() {
        return {
            invoicingDebts: null,
            addresses: {}
        }
    },

    methods: {
        invoice(debts) {
            if (debts) {
                const entity = this.getById(this.entities, debts.items[0].entity);
                this.invoicingDebts = debts;
                this.addresses = entity.addresses;

                return;
            }

            this.invoicingDebts = {};
            this.addresses = [];

        },
        markBilled(debts) {
            this.$emit('markBilled', debts);
        }
    },

    computed: {
        groupedDebts() {
            const debts = {};
            this.debts.forEach((debt) => {
                if (!debt.invoiced) {
                    if (!debts[debt.entityName]) {
                        debts[debt.entityName] = {
                            items: []
                        };
                    }
                    if (!debts[debt.entityName][debt.currency]) {
                        debts[debt.entityName][debt.currency] = 0;
                    }
                    if (!debts[debt.entityName]['nisAmount']) {
                        debts[debt.entityName]['nisAmount'] = 0;
                    }
                    debts[debt.entityName][debt.currency] += debt.totalAmount;
                    debts[debt.entityName]['nisAmount'] += debt.nisAmount;
                    debts[debt.entityName]['items'].push(debt);

                }
            });

            return debts;
        }
    },
}
</script>
