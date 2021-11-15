<template>
    <div>
        <ul class="tabs">
            <li v-for="tab in tabs" :key="tab" v-text="tab" @click="selected=tab" class="tabs__button"
                :class="{'tabs__button--selected': selected===tab}"/>
        </ul>
        <slot :selected="selected" :register="registerTab"/>
    </div>
</template>

<script>
export default {
    name: "Tabs",
    props: {
        startTab: {
            type: String,
            required: false
        }
    },
    data() {
        return {
            selected: null,
            tabs: [],
        }
    },

    methods: {
        registerTab(tab) {
            this.tabs.push(tab);
            if (!this.selected) {
                this.selected = tab;
            }
        },
    }
}
</script>

<style scoped lang="scss">
.tabs {
    display: inline-flex;
    align-items: center;
    border-bottom-color: var(--border-color);
    border-bottom-style: solid;
    border-bottom-width: 1px;
    background-color: var(--card-background);
    border-top-left-radius: var(--radius);
    border-top-right-radius: var(--radius);

    &__button {
        padding: .5em 1em;
        cursor: pointer;
        border-left: 1px solid var(--border-color);

        &:hover:not(.tabs__button--selected) {
            color: var(--link-hover-color);
        }

        &--selected {
            cursor: auto;
            border-bottom-color: var(--link-color);
            color: var(--link-color);
            border-bottom-style: solid;
            border-bottom-width: 1px;
        }
    }
}
</style>
