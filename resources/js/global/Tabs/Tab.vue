<template>
    <div v-show="title === selected">
        <slot/>
    </div>
</template>

<script>
export default {
    name: "Tab",
    props: {
        title: {
            type: String,
            required: true
        },
        selected: {
            required: true
        },
        register: {
            type: Function,
            required: true
        }
    },

    mounted() {
        this.register(this.title);
    }
}
</script>

<style scoped>

</style>
