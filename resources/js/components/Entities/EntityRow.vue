<template>
    <tr class="table__row  table__row--responsive">
        <td class="table__cell table__cell--centered table__cell--narrow table__cell--important"
            :class="{'is-loading': entity.status === 'uploading'}">
            <div class="split-buttons" v-if="withDelete">
                <button class="button is-small split-buttons__button  split-buttons__button--first"
                        @click.stop="$emit('edit')"
                        :class="{'is-success': entity.status === 'saved', 'is-danger': entity.status === 'error'}"
                        :disabled="entity.status === 'deleting'"
                        v-if="entity.status !== 'uploading'">
                    <FontAwesomeIcon :icon="icon" fixed-width/>
                </button>
                <div class="dropdown is-hidden-only-mobile">
                    <button class="button is-small split-buttons__button split-buttons__button--last">
                        <FontAwesomeIcon icon="caret-down" @click.stop="" fixed-width/>
                    </button>
                    <div class="dropdown__content">
                        <button class="button is-small is-danger" @click.stop="$emit('delete')">
                            <FontAwesomeIcon icon="trash" class="button__icon" fixed-width/>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
            <button class=" button is-small" @click.stop="$emit('edit')"
                    :class="{'is-success': entity.status === 'saved', 'is-danger': entity.status === 'error'}"
                    :disabled="entity.status === 'deleting'"
                    v-if="entity.status !== 'uploading' && !withDelete">
                <FontAwesomeIcon :icon="icon" fixed-width/>
            </button>
        </td>
        <td v-text="entity.name" class="table__cell table__cell--important"></td>
    </tr>
</template>

<script>
    export default {
        name: "EntityRow",
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
