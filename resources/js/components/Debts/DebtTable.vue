<template>
    <EditTable :table-data="sortedDebts" title="Debts" :headers="headers" :entry-class="entryClass" @update="update"
               ref="table"
               @delete="destroy"
               :process-entry="setEntityName"
               :form-fields="fields">
        <template #topRightButtons>
            <button class="button is-success" @click="$emit('invoice-settings')">Invoice Settings</button>
        </template>
        <template #filters>
            <div class="chart__filters">
                <DateRangeField v-model="range"/>
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

import DebtRow from "./DebtRow.vue";
import Debt from "../../classes/Models/Debt";
import EditTable from "../../global/Table/EditTable.vue";
import InteractsWithObjects from "../../mixins/InteractsWithObjects";
import SelectField from "../../global/Fields/SelectField.vue";
import CheckboxField from "../../global/Fields/CheckboxField.vue";
import DateRangeField from "../../global/Fields/DateRangeField.vue";

export default {
    name: "DebtTable",
    components: {DateRangeField, CheckboxField, SelectField, DebtRow, EditTable},
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
            range: {
                start: null,
                end: null,
            },
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
            if (this.range.start) {
                const start = new Date(this.range.start);
                debts = debts.filter((debt) => {
                    return debt.date >= start;
                });
            }
            if (this.range.end) {
                const end = new Date(this.range.end);
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
            if (!entry.invoiced) {
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
