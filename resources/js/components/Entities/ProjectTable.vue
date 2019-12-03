<template>
    <div class="card">
        <div class="card__header">
            <h1 class="title is-1" v-text="entity.name"/>
            <button class="close is-large" @click="$emit('close')"/>
        </div>
        <EditTable :table-data="entity.projects" title="Projects"
                   :headers="headers" :entry-class="entryClass"
                   :extra-data="{entity: entity.id}" @update="update" @delete="destroy">
            <template #default="{entry, editEntry, deleteEntry}">
                <ProjectRow :project="entry" :key="`project_${entry.id}`" @edit="editEntry(entry)"
                            @delete="deleteEntry(entry)"/>
            </template>
        </EditTable>
    </div>
</template>

<script>
    import EditTable from "../../global/Table/EditTable";
    import ProjectRow from "./ProjectRow";
    import Project from "../../classes/Models/Project";
    import InteractsWithObjects from "../../mixins/InteractsWithObjects";

    export default {
        name: "ProjectTable",
        components: {ProjectRow, EditTable},
        mixins: [InteractsWithObjects],
        props: {
            entity: {
                type: Object,
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
                entryClass: Project
            }
        },

        methods: {
            update(project) {
                this.updateById(this.entity.projects, project.id, project);
            },

            destroy(project) {
                this.removeById(this.entity.projects, project.id);
            },
        }
    }
</script>
