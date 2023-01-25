<template>
    <div>
        <template v-if="!onlyTable">
            <section class="table__header">
                <div>
                    <h5 class="title is-size-6" v-text="title"></h5>
                </div>
                <div>
                    <button class="button is-success" @click="$emit('add')">
                        + Add
                    </button>
                </div>
            </section>
            <hr>
            <section class="table__filter-wrapper">
                <SelectField class="table__filter" v-model="perPage" :options="{
                 label:'Per page',
                name: 'per-page',
                options: {
                    5: '5',
                    10: '10',
                    20: '20',
                }
                }"/>
                <TextField class="table__filter" v-model="filter" :options="{
                    label: 'Filter'
                }"/>
                <slot name="filters"/>
            </section>
        </template>
        <template v-if="tableData.length">
            <table class="table">
                <thead>
                <tr class="table__row table__row--header table__row--responsive">
                    <td v-for="header in headers"
                        class="table__cell table__cell--header"
                        :class="header.class"
                        :key="header.title" v-text="header.title"/>
                </tr>
                </thead>
                <tbody>
                <slot v-for="(entry, index) in showData" :entry="entry" :index="index"></slot>
                </tbody>
            </table>
            <hr>
        </template>
        <div class="pagination" v-if="tableData.length && !onlyTable">
            <div v-text="paginationText"/>
            <div class="buttons">
                <button v-for="n in pages" v-text="n" class="button" @click="page = (n-1)"
                        :disabled="page === (n-1)"></button>
            </div>
        </div>
    </div>
</template>

<script>
import SelectField from "../Fields/SelectField.vue";
import TextField from "../Fields/TextField.vue";

export default {
    name: "DataTable",
    components: {TextField, SelectField},

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
        onlyTable: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            perPage: parseInt(localStorage.getItem(`${this.title}-per-page`)) || 5,
            page: 0,
            filter: ''
        }
    },

    computed: {
        filteredData() {
            let data = this.tableData;
            if (this.filter) {
                data = data.filter((entry) => {
                    for (let prop in this.headers) {
                        const header = this.headers[prop];
                        if (header.filterable) {
                            if (entry[header.name].toLowerCase().indexOf(this.filter.toLowerCase()) > -1) {
                                return true;
                            }
                        }
                    }
                    return false;
                });
            }
            return data;
        },
        showData() {
            return this.filteredData.slice(this.pageStart, this.pageEnd);
        },
        pageStart() {
            return this.page * this.perPage;
        },

        pageEnd() {
            return Math.min(this.page * this.perPage + this.perPage, this.totalEntries);
        },

        pages() {
            return Math.ceil(this.totalEntries / this.perPage);
        },

        totalEntries() {
            return this.filteredData.length;
        },

        paginationText() {
            return `Showing ${this.pageStart + 1} to ${this.pageEnd} of ${this.totalEntries} entries`;
        }
    },

    watch: {
        perPage(value) {
            this.page = 0;
            localStorage.setItem(`${this.title}-per-page`, value);
        }
    }
}
</script>
