<template>
    <metainfo>
        <template #title="{ content }">{{ content }} | El-Coop</template>
    </metainfo>
    <div class="app" :class="`theme-${theme}`">
        <div class="spa-loader" v-if="loader">
            <div class="spa-loader__animation">
            </div>
        </div>
        <navbar v-if="$store.getters['auth/loggedIn']"></navbar>
        <div class="app-content" v-if="! loader">
            <router-view v-cloak></router-view>
        </div>
    </div>
</template>

<script>
export default {
    name: "Root",
    metaInfo: {
        // if no subcomponents specify a metaInfo.title, this title will be used
        title: 'Loading'
    },
    data() {
        return {
            loader: false,
        }
    },
    beforeCreate() {
        this.$store.dispatch('init');
    },

    computed: {
        theme() {
            return this.$store.state.theme;
        }
    },
}
</script>
