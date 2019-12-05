<template>
    <div @click="hidden = ! hidden">
        {
        <span v-if="hidden">...</span>
        <template v-else>
            <div v-for="(value, key) in json" @click.stop>
                <b v-text="`${key}:`"/>
                <span v-if="typeof value !== 'object'" v-text="value || 'Null'"/>
                <JsonViewer v-else :json="value" class="inner-json"/>
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
<style scoped>
    .inner-json {
        margin-left: var(--size-7);
    }
</style>
