<template>
    <div class="split-buttons">
        <button class="button is-small split-buttons__button  split-buttons__button--first"
                @click.stop="$emit('edit')"
                :class="{'is-success': entry.status === 'saved', 'is-danger': entry.status === 'error'}"
                :disabled="entry.status === 'deleting'"
                v-if="entry.status !== 'uploading'">
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
</template>

<script>
    export default {
        name: "SplitActionButtons",
        props: {
            entry: {
                required: true,
                type: Object
            }
        },
        computed: {
            icon() {
                if (this.entry.status === 'error') {
                    return 'exclamation';
                }

                return 'edit'
            },
        }
    }
</script>
