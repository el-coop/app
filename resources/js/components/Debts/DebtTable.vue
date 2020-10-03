<template>
    <EditTable :table-data="debts" title="Debts" :headers="headers" :entry-class="entryClass" @update="update">
        <template #default="{entry, editEntry, deleteEntry}">
            <DebtRow :entity="entry" :key="`entity_${entry.id}`" @edit="editEntry(entry)"
                       @select="showEntityProjects(entry)" @delete="deleteEntry(entry)"/>
        </template>
    </EditTable>
</template>

<script>

import DebtRow from "./DebtRow";
import Entity from "../../classes/Models/Debt";
import EditTable from "../../global/Table/EditTable";

export default {
    name: "EntityTable",
    components: {DebtRow, EditTable},
    props: {
        debts: {
            type: Array,
            required: true
        }
    },

    data() {
        return {
            headers: [{
                title: 'Action',
                class: 'table__cell--narrow table__cell--important'
            }, {
                title: 'Name',
                class: 'table__cell--important',
                name: 'name',
                filterable: true
            }],
            entryClass: Entity
        }
    },

    methods: {
        update(entity) {
            this.$emit('update', entity);
        },

        showEntityProjects(entity) {
            this.$emit('select', entity);
        }
    }
}
</script>

<style scoped>
.wrapper {
    margin-left: 1em;
}
</style>
