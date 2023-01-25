<template>
    <div class="card">
        <EditTable class="reverse-order" :table-data="sortedTransactions" title="Transactions" :form-fields="fields"
                   :headers="headers" :entry-class="entryClass" @update="update" @delete="destroy">
            <template #default="{entry, index, editEntry, deleteEntry, toggleActive, active}">
                <TransactionRow :key="`transaction_${entry.id}`"
                                :class="{'table__row--active': active}"
                                @toggle="toggleActive(index)"
                                @edit="editEntry(entry)"
                                @delete="deleteEntry(entry)"
                                :entities="entities"
                                :transaction="entry"/>
                <tr class="table__row--details" :key="`transaction_${entry.id}_details`">
                    <div class="table__row--details-content">
                        <div class="table__cell table__cell--details" data-label="Rate"
                             v-text="entry.rate"></div>
                        <div class="table__cell table__cell--details" data-label="Money"
                             v-text="`${entry.currency}${entry.amount}`"></div>
                    </div>
                </tr>
            </template>
        </EditTable>
    </div>
</template>

<script>

    import Transaction from "../../classes/Models/Transaction";
    import TransactionRow from "./TransactionRow.vue";
    import EditTable from "../../global/Table/EditTable.vue";

    export default {
        name: "TransactionTable",
        components: {EditTable, TransactionRow},

        props: {
            transactions: {
                type: Array,
                required: true
            },
            entities: {
                type: Array,
                required: true
            }
        },

        data() {
            return {
                headers: [{
                    title: 'Action',
                    class: 'table__cell--narrow'
                }, {
                    title: 'Date',
                    class: 'table__cell--narrow'
                }, {
                    title: 'To/From',
                    class: 'table__cell--important',
                    name: 'entityName',
                    filterable: true
                }, {
                    title: 'Reason',
                    class: '',
                    name: 'reason',
                    filterable: true
                }, {
                    title: 'Amount',
                    class: 'table__cell--right table__cell--important'
                }],
                entryClass: Transaction
            }
        },

        computed: {
            sortedTransactions() {
                return this.transactions.sort((a, b) => {
                    return b.date - a.date;
                });
            },
            fields() {
                const entityOptions = {};
                this.entities.forEach((value) => {
                    entityOptions[value.id] = value.name;
                });

                const fields = Transaction.fields();
                const field = fields.find((fieldOptions) => {
                    return fieldOptions.name === 'entity';
                });

                field.options = entityOptions;
                return fields;
            }
        },

        methods: {
            destroy(transaction) {
                this.$emit('delete', transaction);
            },

            update(transaction) {
                this.$emit('update', transaction);
            },
        },
    }
</script>

<style scoped>
    .reverse-order {
        display: flex;
        flex-direction: column-reverse;
    }
</style>
