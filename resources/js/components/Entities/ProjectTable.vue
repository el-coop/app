<template>
    <div class="card">
        <transition name="fade" mode="out-in">
            <ErrorTable key="errors_table" v-if="selectedProject" :project="selectedProject"
                        @close="selectedProject = null"/>
            <div key="projects_table" v-else>
                <div class="card__header">
                    <h1 class="title is-1" v-text="entity.name"/>
                </div>
                <EditTable :table-data="entity.projects" title="Projects"
                           :headers="headers" :entry-class="entryClass"
                           :extra-data="{entity: entity.id}" @update="update" @delete="destroy">
                    <template #default="{entry, index, editEntry, deleteEntry, toggleActive, active}">
                        <ProjectRow :project="entry" :key="`project_${entry.id}`" @edit="editEntry(entry)"
                                    :class="{'table__row--active': active}"
                                    @view="selectedProject = entry"
                                    @toggle="toggleActive(index)"
                                    @delete="deleteEntry(entry)"/>
                    </template>
                </EditTable>
            </div>
        </transition>
    </div>
</template>

<script>
    import EditTable from "../../global/Table/EditTable.vue";
    import ProjectRow from "./ProjectRow.vue";
    import Project from "../../classes/Models/Project";
    import InteractsWithObjects from "../../mixins/InteractsWithObjects";
    import Modal from "../../global/Modal.vue";
    import ErrorTable from "./ErrorTable.vue";

    export default {
        name: "ProjectTable",
        components: {ErrorTable, ProjectRow, EditTable, Modal},
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
                    class: 'table__cell--narrow'
                }, {
                    title: 'Name',
                    class: 'table__cell--important',
                    name: 'name',
                    filterable: true
                }],
                entryClass: Project,
                selectedProject: null,
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
