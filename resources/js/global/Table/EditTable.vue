<template>
    <div class="wrapper">
        <template v-for="(currentData,index) in [tableData, pending]">
            <DataTable :title="index ? `${title}_pending` : title" :table-data="currentData" :headers="headers"
                       :only-table="!! index">
                <template #default="{entry, index}">
                    <component :is="rowComponent" :key="`${title}_${index}_${entry.id}`" :entry="entry"/>
                </template>
            </DataTable>
        </template>
    </div>
</template>

<script>
    import DataTable from "./DataTable";

    export default {
        name: "EditTable",
        components: {DataTable},
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
            rowComponent: {
                type: Object,
                required: true
            }
        },

        data() {
            return {
                pending: [],
            }
        }
    }
</script>
