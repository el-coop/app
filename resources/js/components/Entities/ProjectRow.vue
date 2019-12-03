<template>
    <tr class="table__row  table__row--responsive" @click="$emit('select')">
        <td class="table__cell table__cell--centered table__cell--narrow table__cell--important"
            :class="{'is-loading': project.status === 'uploading'}">
            <SplitActionButtons :entry="project" @edit="$emit('edit')" @delete="$emit('delete')"/>
        </td>
        <td v-text="project.name" class="table__cell table__cell--important"/>
        <td v-text="project.token || ''" data-label="Token" class="table__cell table__cell--clickable"
            @click="copyToken"/>
    </tr>
</template>

<script>
    import SplitActionButtons from "../../global/Table/SplitActionButtons";

    export default {
        name: "ProjectRow",
        components: {SplitActionButtons},
        props: {
            project: {
                type: Object,
                required: true
            }
        },

        methods: {
            async copyToken() {
                try {
                    await navigator.clipboard.writeText(this.project.token);
                    this.$toast.info('Use it when you information to the project', 'Token copied');
                } catch (error) {
                    this.$toast.error('Please get token by marking on it and clicking ctrl+c', 'Copying failed');
                }
            }
        }
    }
</script>

<style scoped>
    .table__cell--clickable {
        cursor: pointer;
    }
</style>
