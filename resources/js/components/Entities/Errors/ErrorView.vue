<template>
    <div>
        <div class="card__header">
            <h3 class="title is-3" v-text="errorEntry.message"/>
            <span v-text="`(${entryIndex+1}/${this.errorEntry.entries.length})`"/>
        </div>
        <div class="carousel">
            <button class="button carousel__arrow carousel__arrow--prev" @click="changeEntry(-1)">
                &lt;
            </button>
            <div class="carousel__content">
                <div v-for="(value, label) in selectedEntry" class="field">
                    <label class="label" v-text="label.charAt(0).toUpperCase() + label.substring(1).replace('_',' ')"/>
                    <div v-if="typeof value !== 'object'" class="code" v-text="value"/>
                    <JsonViewer v-else class="code" :json="value || []"/>
                </div>
            </div>
            <button class="button carousel__arrow carousel__arrow--next" @click="changeEntry(+1)">
                >
            </button>
        </div>
    </div>
</template>

<script>
    import TextField from "../../../global/Fields/TextField";
    import JsonViewer from "../../../global/JsonViewer";

    export default {
        name: "ErrorView",
        components: {JsonViewer, TextField},
        props: {
            errorEntry: {
                required: true,
                type: Object
            }
        },

        data() {
            return {
                entryIndex: 0
            }
        },

        computed: {
            selectedEntry() {
                return this.errorEntry.entries[this.entryIndex];
            }
        },

        methods: {
            changeEntry(amount) {
                this.entryIndex += amount;
                const length = this.errorEntry.entries.length;
                if (this.entryIndex < 0) {
                    return this.entryIndex = length - 1;
                }
                if (this.entryIndex >= length) {
                    return this.entryIndex = 0;
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .code {
        background-color: var(--chart-background-color);
        border-radius: var(--radius);
        color: var(--title-color);
        padding: var(--size-7);
    }

    .carousel {
        display: grid;
        grid-template-rows: 1fr;
        grid-template-columns: min-content 1fr min-content;
        position: relative;

        &__content {
            flex: 1;
            overflow-y: auto;
        }

        &__arrow {
            padding: 1em 0.5em;
            height: 100%;

            &--prev {
                left: -1em;
            }

            &--next {
                right: -1em;
            }

            &:hover {
                opacity: 1;
            }
        }
    }
</style>
