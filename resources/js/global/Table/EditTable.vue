<template>
    <div>
        <template v-for="(currentData,tableIndex) in [tableData, pending]">
            <DataTable :title="tableIndex ? `${title}_pending` : title" :table-data="currentData" :headers="headers"
                       :only-table="!! tableIndex" @add="editEntry()">
                <template #topRightButtons>
                    <slot name="topRightButtons"/>
                </template>
                <template #default="{entry, index}">
                    <slot :entry="entry" :index="index" :editEntry="editEntry" :deleteEntry="deleteEntry"
                          :active="active === index" :toggleActive="toggleActive"/>
                </template>
                <template #filters v-if="tableIndex === 0">
                    <slot name="filters"/>
                </template>
            </DataTable>
        </template>
        <Modal v-model:active="form" @update:active="selectedEntry=null">
            <EditForm v-if="selectedEntry" v-model:entry="selectedEntry" :fields="fields" @update:entry="updateEntry"/>
        </Modal>
    </div>
</template>

<script>
import DataTable from "./DataTable.vue";
import Modal from "../Modal.vue";
import EditForm from "../Forms/EditForm.vue";
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
        processEntry: {
            type: Function,
        }
    },

    data() {
        return {
            form: false,
            selectedEntry: null,
            active: null,
            pending: this.loadPending(),
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
            if (this.processEntry) {
                this.processEntry(entry);
            }

            const savedId = entry.id;
            this.saveUpdateEntry(entry);

            this.updateById(this.pending, entry.id, entry);
            this.selectedEntry = null;
            this.form = false;
            const response = await entry.save();
            if (entry.status === 'saved') {
                this.deleteUpdateEntry(savedId);
                this.removeById(this.pending, entry.id);
                this.$emit('update', entry);
                this.$toast.success(' ', 'Entry saved');
            } else {
                if (response.status !== 401 && response.data.message !== 'Unauthenticated.') {
                    this.$toast.error('Please try again', 'Entry save error');
                }
                this.saveUpdateEntry(entry);
            }
        },

        async deleteEntry(entry) {
            if (this.pending.indexOf(entry) > -1) {
                this.removeById(this.pending, entry.id);
                this.deleteUpdateEntry(entry.id)
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
        saveUpdateEntry(entry) {
            const savedEntry = Object.assign({}, entry);
            savedEntry.files = [];
            localStorage.setItem(`${this.title}_pending_${entry.id}`, JSON.stringify(savedEntry));
        },
        deleteUpdateEntry(entryId) {
            localStorage.removeItem(`${this.title}_pending_${entryId}`);

        },
        loadPending() {
            const entries = [];
            const regexp = new RegExp(`${this.title}_pending_`);
            for (let i in localStorage) {
                if (localStorage.hasOwnProperty(i)) {
                    if (i.match(regexp)) {
                        const parsedObject = JSON.parse(localStorage.getItem(i));
                        parsedObject.fromLocalStorage = true;
                        entries.push(new this.entryClass(parsedObject));
                    }
                }
            }
            return entries;
        }

    },
}
</script>
