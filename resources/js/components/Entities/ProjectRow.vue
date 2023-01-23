<template>
    <tr class="table__row  table__row--responsive">
        <td class="table__cell table__cell--centered table__cell--narrow table__cell--action"
            :class="{'is-loading': project.status === 'uploading' || project.status === 'deleting'}">
            <SplitActionButtons :entry="project" @edit="$emit('edit')" @delete="$emit('delete')">
                <button class="button is-small is-info dropdown__content-button" @click.stop="$emit('view')">
                    <FontAwesomeIcon icon="list" class="button__icon" fixed-width/>
                    Errors
                </button>
            </SplitActionButtons>
        </td>
        <td v-text="project.name" class="table__cell table__cell--important" @click="$emit('toggle')"/>
        <td data-label="Token" class="table__cell table__cell--clickable"
            @click.prevent="copyToken">
            <input :value="project.token" class="token input" readonly/>
        </td>
        <td class="table__cell table__cell--right table__cell--action is-hidden-tablet">
            <button class="button is-small is-danger" :class="{ 'is-loading': project.status === 'deleting'}"
                    @click.stop="$emit('delete')">
                <FontAwesomeIcon icon="trash" fixed-width/>
            </button>
        </td>
    </tr>
</template>

<script>
    import SplitActionButtons from "../../global/Table/SplitActionButtons.vue";

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
                    this.$el.querySelector('.token').select();
                    this.$toast.info('Click ctrl+c to copy manually (Click and hold on mobile)', 'Copying failed');
                }
            }
        }
    }
</script>

<style scoped>
    .token {
        word-break: break-all;
    }
</style>
