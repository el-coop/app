<template>
    <div class="card">
        <div class="card__header">
            <h1 class="title is-1" v-text="entity.name"/>
            <button class="close is-large" @click="$emit('close')"/>
        </div>
        <EditTable :table-data="entity.projects" title="Projects" :row-component="rowComponent" :headers="headers"/>
    </div>
</template>

<script>
    import EditTable from "../../global/Table/EditTable";
    import ProjectRow from "./ProjectRow";


    export default {
        name: "ProjectTable",
        components: {EditTable},
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
                rowComponent: ProjectRow
            }
        }
    }
</script>
