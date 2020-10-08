<template>
    <div>
        <template v-for="(currentData,tableIndex) in [tableData, pending]">
            <DataTable :title="tableIndex ? `${title}_pending` : title" :table-data="currentData" :headers="headers"
                       :only-table="!! tableIndex" @add="editEntry()">
                <template #default="{entry, index}">
                    <slot :entry="entry" :index="index" :editEntry="editEntry" :deleteEntry="deleteEntry"
                          :active="active === index" :toggleActive="toggleActive"/>
                </template>
                <template #filters v-if="tableIndex === 0">
                    <slot name="filters"/>
                </template>
            </DataTable>
        </template>
        <Modal :active.sync="form" @update:active="selectedEntry=null">
            <EditForm v-if="selectedEntry" v-model="selectedEntry" :fields="fields" @update="updateEntry"/>
        </Modal>
    </div>
</template>

<script>
    import DataTable from "./DataTable";
    import Modal from "../Modal";
    import EditForm from "../Forms/EditForm";
    import InteractsWithObjects from "../../mixins/InteractsWithObjects";

    export default {
        name: "EditTable",
        components: {DataTable, Modal, EditForm},
        mixins: [InteractsWithObjects],
        props: {
            title: {
                type: String,
                required: true
            },
            tableData: {
                type: Array,
                required: true
            },
            headers: {
                type: Array,
                required: true
            },
            entryClass: {
                type: Function,
                required: true
            },
            extraData: {
                type: Object,
                default() {
                    return {};
                }
            },
            formFields: {
                type: Array
            },
            processEntry:{
                type: Function,
            }
        },

        data() {
            return {
                pending: [],
                form: false,
                selectedEntry: null,
                active: null
            }
        },

        computed: {
            fields() {
                return this.formFields || this.entryClass.fields();
            }
        },

        methods: {
            toggleActive(index) {
                if (this.active === index) {
                    return this.active = null;
                }
                this.active = index;
            },

            editEntry(entry) {
                if (!entry) {
                    entry = new this.entryClass(this.extraData);
                }

                this.selectedEntry = entry;
                this.form = true;
            },

            async updateEntry() {
                const entry = this.selectedEntry;

                if(this.processEntry){
                    this.processEntry(entry);
                }

                this.updateById(this.pending, entry.id, entry);
                this.selectedEntry = null;
                this.form = false;
                const response = await entry.save();
                if (entry.status === 'saved') {
                    this.removeById(this.pending, entry.id);
                    this.$emit('update', entry);
                    this.$toast.success(' ', 'Entry saved');
                } else {
                    if (response.status !== 401 && response.data.message !== 'Unauthenticated.') {
                        this.$toast.error('Please try again', 'Entry save error');
                    }
                }
            },

            async deleteEntry(entry) {
                if (this.pending.indexOf(entry) > -1) {
                    this.removeById(this.pending, entry.id);

                    return;
                }
                const response = await entry.delete();

                if (response) {
                    this.$emit('delete', entry);
                    this.$toast.success(' ', 'Entry deleted');
                    return;
                }
                this.$toast.error('Please try again', 'Entry delete error');

            },
        }
    }
</script>
