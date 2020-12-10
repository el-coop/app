<template>
    <EditTable :table-data="sortedDebts" title="Debts" :headers="headers" :entry-class="entryClass" @update="update"
               ref="table"
               @delete="destroy"
               :process-entry="setEntityName"
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
            <CheckboxField class="table__filter-field" :options="{
                label: 'Show billed?'
            }" v-model="showBilled"/>
        </template>
        <template #default="{entry, editEntry, deleteEntry}">
            <DebtRow :debt="entry" :key="`entity_${entry.id}`" :with-delete="true"
                     @edit="editEntry(entry)"
                     @delete="deleteEntry(entry)"
                     @toggle-billed="toggleBilled(entry)"
                     :entities="entities"/>
        </template>
    </EditTable>
</template>

<script>

import DebtRow from "./DebtRow";
import Debt from "../../classes/Models/Debt";
import EditTable from "../../global/Table/EditTable";
import TextField from "../../global/Fields/TextField";
import InteractsWithObjects from "../../mixins/InteractsWithObjects";
import SelectField from "../../global/Fields/SelectField";
import CheckboxField from "../../global/Fields/CheckboxField";

export default {
    name: "DebtTable",
    components: {CheckboxField, SelectField, DebtRow, EditTable, TextField},
    mixins: [InteractsWithObjects],
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
                title: 'Comment',
                name: 'comment'
            }, {
                title: 'Amount',
                class: 'table__cell--right table__cell--important'
            }],
            startDateError: null,
            endDateError: null,
            start: null,
            end: null,
            entryClass: Debt,
            showBilled: false
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
            if (!this.showBilled) {
                debts = debts.filter((debt) => {
                    return !debt.invoiced;
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
        },
    },

    methods: {
        setEntityName(debt) {
            const entity = this.getById(this.entities, debt.entity);
            debt.entityName = entity ? entity.name : '';
        },
        update(debt) {
            this.$emit('update', debt);
        },

        destroy(debt) {
            this.$emit('delete', debt);
        },
        toggleBilled(entry) {
            if(! entry.invoiced){
                entry.invoiced = 1;
            } else {
                entry.invoiced = 0;
            }
            this.$refs.table.selectedEntry = entry;
            this.$refs.table.updateEntry(entry);
        }

    }
}
</script>
