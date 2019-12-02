<template>
    <tr class="table__row  table__row--responsive" @click="$emit('select')">
        <td class="table__cell table__cell--centered table__cell--narrow table__cell--important"
            :class="{'is-loading': entry.status === 'uploading'}">
            <SplitActionButtons :entry="entry"/>
        </td>
        <td v-text="entry.name" class="table__cell table__cell--important"/>
    </tr>
</template>

<script>
    import SplitActionButtons from "../../global/Table/SplitActionButtons";

    export default {
        name: "ProjectRow",
        components: {SplitActionButtons},
        props: {
            entry: {
                type: Object,
                required: true
            }
        },

    }
</script>
