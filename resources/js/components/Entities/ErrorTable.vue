<template>
    <div :class="{'is-loading': loading}">
        <div class="card__header">
            <h1 class="title is-1" v-text="`${project.name}`"/>
            <button class="close is-large" @click="$emit('close')"/>
        </div>
        <DataTable :table-data="project.projectErrors" :headers="headers" title="Errors">
            <template #default="{entry, index}">
                <ErrorRow :key="`error_${index}`" :entry="entry" @view="view(entry)" @delete="deleteEntry(entry)"/>
            </template>
        </DataTable>
        <Modal v-model:active="viewModal" @update:active="viewed=null" :wide="true">
            <ErrorView v-if="viewed" :error-entry="viewed"/>
        </Modal>
    </div>
</template>

<script>
    import Modal from "../../global/Modal.vue";
    import ErrorRow from "./Errors/ErrorRow.vue";
    import ErrorView from "./Errors/ErrorView.vue";
    import InteractsWithObjects from "../../mixins/InteractsWithObjects";
    import DataTable from "../../global/Table/DataTable.vue";

    export default {
        name: "ErrorTable",
        components: {ErrorRow, Modal, ErrorView, DataTable},
        mixins: [InteractsWithObjects],

        props: {
            project: {
                required: true,
                type: Object
            }
        },

        data() {
            return {
                loading: false,
                headers: [{
                    title: 'Action',
                    class: 'table__cell--narrow'
                }, {
                    title: 'Created at',
                    name: 'created_at',
                    class: 'table__cell',
                }, {
                    title: 'Url',
                    name: 'url',
                    class: 'table__cell--important',
                    filterable: true
                }, {
                    title: 'Message',
                    name: 'message',
                    class: 'table__cell--important',
                    filterable: true
                }],
                viewed: null,
                viewModal: false,
            }
        },

        async created() {
            this.loading = true;
            const success = await this.project.loadErrors();
            if (!success) {
                this.$toast.error('Please refresh page', 'Project error loading error');
            }
            this.loading = false;
        },

        methods: {
            view(entry) {
                this.viewed = entry;
                this.viewModal = true;
            },
            async deleteEntry(entry) {
                const response = await entry.delete();

                if (response) {
                    this.removeById(this.project.projectErrors, entry.id);
                    this.$toast.success(' ', 'Entry deleted');
                    return;
                }
                this.$toast.error('Please try again', 'Entry delete error');
            }
        }
    }
</script>
