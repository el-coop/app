<template>
    <EditTable :table-data="entities" title="Projects" :headers="headers" :entry-class="entryClass" @update="update">
        <template #default="{entry, editEntry, deleteEntry}">
            <EntityRow :entity="entry" :key="`entity_${entry.id}`" @edit="editEntry(entry)"
                       @select="showEntityProjects(entry)" @delete="deleteEntry(entry)"
                       :with-delete="true"/>
        </template>
    </EditTable>
</template>

<script>

    import EntityRow from "./EntityRow";
    import Entity from "../../classes/Models/Entity";
    import EditTable from "../../global/Table/EditTable";

    export default {
        name: "EntityTable",
        components: {EntityRow, EditTable},
        props: {
            entities: {
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
            },

            destroy(entity) {
                this.$emit('delete', entity);
            },

        }
    }
</script>

<style scoped>
    .wrapper {
        margin-left: 1em;
    }
</style>
