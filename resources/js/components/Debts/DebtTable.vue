<template>
    <EditTable :table-data="sortedDebts" title="Debts" :headers="headers" :entry-class="entryClass" @update="update"
               @delete="destroy"
               :form-fields="fields">
        <template #filters>
            <div class="chart__filters">
                <TextField class="field--marginless" :options="{
                type: 'date',
                format: 'dd/mm/yyyy',
            }" :error="startDateError" v-model="start"/>
                <span> - </span>
                <TextField class="field--marginless" :options="{
                type: 'date',
                format: 'dd/mm/yyyy'
             }" :error="endDateError" v-model="end"/>
            </div>
        </template>
        <template #default="{entry, editEntry, deleteEntry}">
            <DebtRow :debt="entry" :key="`entity_${entry.id}`" :with-delete="true"
                     @edit="editEntry(entry)"
                     @delete="deleteEntry(entry)"
                     :entities="entities"/>
        </template>
    </EditTable>
</template>

<script>

import DebtRow from "./DebtRow";
import Debt from "../../classes/Models/Debt";
import EditTable from "../../global/Table/EditTable";
import TextField from "../../global/Fields/TextField";

export default {
    name: "DebtTable",
    components: {DebtRow, EditTable, TextField},
    props: {
        debts: {
            type: Array,
            required: true
        },
        entities: {
            type: Array,
            required: true
        },
        groupedProjects: {
            type: Object,
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
                title: 'Billed',
                class: 'table__cell--narrow',
                name: 'invoiced',
            }, {
                title: 'From',
                class: 'table__cell--important',
                name: 'entityName',
                filterable: true
            }, {
                title: 'Amount',
                class: 'table__cell--right table__cell--important'
            }],
            startDateError: null,
            endDateError: null,
            start: null,
            end: null,
            entryClass: Debt
        }
    },

    computed: {
        sortedDebts() {
            return this.filteredDebts.sort((a, b) => {
                return b.date - a.date;
            });
        },
        filteredDebts() {
            let debts = this.debts;
            if (this.start) {
                const start = new Date(this.start);
                debts = debts.filter((debt) => {
                    return debt.date >= start;
                });
            }
            if (this.end) {
                const end = new Date(this.end);
                debts = debts.filter((debt) => {
                    return debt.date <= end;
                });
            }
            return debts;
        },
        fields() {
            const entityOptions = {};
            this.entities.forEach((value) => {
                entityOptions[`${value.id} `] = value.name;
            });

            const fields = Debt.fields();
            const entityField = fields.find((fieldOptions) => {
                return fieldOptions.name === 'entity';
            });

            entityField.options = entityOptions;

            const projectField = fields.find((fieldOptions) => {
                return fieldOptions.name === 'project';
            });

            projectField.groupedProjects = this.groupedProjects;

            return fields;
        }
    },

    methods: {
        update(entity) {
            this.$emit('update', entity);
        },

        destroy(transaction) {
            this.$emit('delete', transaction);
        },

    }
}
</script>

<style scoped>
.wrapper {
    margin-left: 1em;
}
</style>
