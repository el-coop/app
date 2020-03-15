<template>
    <tr class="table__row  table__row--responsive">
        <td class="table__cell table__cell--centered table__cell--narrow table__cell--important"
            :class="{'is-loading': entity.status === 'uploading' || entity.status === 'deleting'}">
            <SplitActionButtons v-if="withDelete" :entry="entity" @delete="$emit('delete')" @edit="$emit('edit')"/>
            <button class="button is-small" @click.stop="$emit('edit')"
                    :class="{'is-success': entity.status === 'saved', 'is-danger': entity.status === 'error'}"
                    :disabled="entity.status === 'deleting'"
                    v-if="entity.status !== 'uploading' && !withDelete">
                <FontAwesomeIcon :icon="icon" fixed-width/>
            </button>
        </td>
        <td class="table__cell table__cell--important table__cell--clickable" v-text="entity.name"
            @click="$emit('select')"/>
    </tr>
</template>

<script>
    import SplitActionButtons from "../../global/Table/SplitActionButtons";

    export default {
        name: "EntityRow",
        components: {SplitActionButtons},
        props: {
            entity: {
                type: Object,
                required: true
            },
            withDelete: {
                type: Boolean,
                default: false
            }
        },

        computed: {
            icon() {
                if (this.entity.status === 'error') {
                    return 'exclamation';
                }

                return 'edit'
            },
        }
    }
</script>
