<template>
    <div class="split-buttons">
        <button class="button is-small split-buttons__button split-buttons__button--first"
                @click.stop="$emit('edit')"
                :class="{'is-success': entry.status === 'saved', 'is-danger': entry.status === 'error'}"
                :disabled="entry.status === 'deleting' || entry.status === 'uploading'">
            <FontAwesomeIcon :icon="icon" fixed-width/>
        </button>
        <div class="dropdown is-hidden-only-mobile">
            <button class="button is-small split-buttons__button split-buttons__button--last">
                <FontAwesomeIcon icon="caret-down" @click.stop fixed-width/>
            </button>
            <div class="dropdown__content">
                <slot/>
                <button class="button is-small is-danger dropdown__content-button" @click.stop="destroy">
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
        },
        deleteConfirmation: {
            type: String,
            default: ''
        }
    },
    computed: {
        icon() {
            if (this.entry.status === 'error') {
                return 'exclamation';
            }

            return 'edit'
        },
    },
    methods: {
        async destroy() {
            if (!this.deleteConfirmation) {
                this.$emit('delete');
            }

            const confirmed = await new Promise((resolve) => {
                this.$toast.question(this.deleteConfirmation, '', {
                    class: 'iziToast-buttons-new-line',
                    position: 'center',
                    timeout: false,
                    buttons: [
                        ['<button><b>Confirm</b></button>', function (instance, toast) {
                            instance.hide({transitionOut: 'fadeOut'}, toast, 'button');
                            resolve(true);
                        }],
                        ['<button>Cancel</button>', function (instance, toast) {
                            instance.hide({transitionOut: 'fadeOut'}, toast, 'button');
                            resolve(false);
                        }, true],
                    ]
                });
            })

            if(confirmed){
                this.$emit('delete');
            }
        }
    }
}
</script>

<style lang="scss">
@import "~bulma/sass/utilities/initial-variables";
@import "~bulma/sass/utilities/functions";
@import "~bulma/sass/utilities/derived-variables";
@import "~bulma/sass/utilities/mixins";
@import "../../../sass/variables";

.split-buttons {
    width: 100%;

    @include until($mobile) {
        &__button {
            width: 100%;
            border-radius: var(--radius);
        }
    }
}

</style>
