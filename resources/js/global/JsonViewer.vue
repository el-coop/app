<template>
    <div @click="hidden = ! hidden" class="json">
        {
        <span v-if="hidden && Object.values(json).length">...</span>
        <template v-if="!hidden">
            <div v-for="(value, key) in json" @click.stop>
                <b v-text="`${key}:`"/>
                <span v-if="typeof value !== 'object'" class="json__value" v-text="value"/>
                <JsonViewer v-else-if="value && Object.values(value).length" :json="value" class="json__inner"/>
                <span v-else>{ }</span>
            </div>
        </template>
        }
    </div>
</template>

<script>
    export default {
        name: "JsonViewer",

        props: {
            json: {
                type: [Object, Array],
                required: true
            },
            startHidden: {
                type: Boolean,
                default: true
            }
        },

        data() {
            return {
                hidden: this.startHidden
            }
        }
    }
</script>
<style lang="scss" scoped>
    .json {
        &__inner {
            margin-left: var(--size-7);
        }

        &__value {
            white-space: pre;
        }

        overflow-x: auto;
    }
</style>
