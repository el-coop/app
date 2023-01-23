<template>
    <div class="container" :class="{'is-loading': loading}">
        <div class="background-content" ref="backgroundContent" :class="{'background-content--small': selectedEntity}"
             :style="{height: backgroundContentHeight}">
            <transition name="fade">
                <EntityTable v-show="!selectedEntity" :entities="entities" @update="update" @select="viewProjects"
                             @delete="destroy"/>
            </transition>
        </div>
        <div class="foreground-content">
            <transition name="fade">
                <Tabs v-if="selectedEntity">
                    <template #rightSide>
                        <button class="close is-large " @click="selectedEntity = null"/>
                    </template>
                    <template #default="{selected, register}">
                        <Tab :selected="selected" :register="register" title="Notes">
                            <Notes :entity="selectedEntity"/>
                        </Tab>

                        <Tab :selected="selected" :register="register" title="Projects">
                            <ProjectTable :entity="selectedEntity" @close="selectedEntity = null"/>
                        </Tab>
                    </template>
                </Tabs>
            </transition>
        </div>
    </div>
</template>

<script>
import Entity from "../classes/Models/Entity";
import EntityTable from "../components/Entities/EntityTable.vue";
import InteractsWithObjects from "../mixins/InteractsWithObjects";
import ProjectTable from "../components/Entities/ProjectTable.vue";
import Tab from "../global/Tabs/Tab.vue";
import Tabs from "../global/Tabs/Tabs.vue";
import Notes from "../components/Entities/Notes.vue";

export default {
    name: "Entities",
    components: { ProjectTable, EntityTable, Tabs, Tab, Notes},
    mixins: [InteractsWithObjects],
    metaInfo: {
        title: 'Entities'
    },

    created() {
        this.load();
    },

    data() {
        return {
            entities: [],
            loading: false,
            selectedEntity: null,
            backgroundContentHeight: 'auto',
        }
    },

    mounted() {
        this.$refs.backgroundContent.addEventListener('transitionend', this.backgroundTransitionListener);
    },

    beforeUnmount() {
        this.$refs.backgroundContent.removeEventListener('transitionend', this.backgroundTransitionListener);
    },

    methods: {
        async load() {
            this.loading = true;
            if (!this.entities.length) {
                this.entities = await Entity.list();
            }
            this.loading = false;

        },
        update(entity) {
            this.updateById(this.entities, entity.id, entity);
        },
        destroy(entity) {
            this.removeById(this.entities, entity.id);
        },

        async viewProjects(entity) {
            this.backgroundContentHeight = this.$refs.backgroundContent.clientHeight + 'px';
            await this.nextTick;
            setTimeout(() => {
                this.selectedEntity = entity;
            }, 100);
        },

        backgroundTransitionListener(event) {
            if (event.propertyName === 'height' && !this.selectedEntity) {
                this.backgroundContentHeight = 'auto';
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import "node_modules/bulma/sass/utilities/initial-variables";
@import "node_modules/bulma/sass/utilities/functions";
@import "node_modules/bulma/sass/utilities/derived-variables";
@import "node_modules/bulma/sass/utilities/mixins";
@import "../../sass/variables";

.container {
    display: flex;
    flex-direction: column-reverse;

    @include from($mobile) {
        display: block;
    }
}

.background-content {
    padding-left: 2em;

    &--small {
        display: none;
    }

    @include from($mobile) {
        display: block;
        will-change: height;
        transition: height 1s;
        overflow: hidden;

        &--small {
            height: 60px !important;
        }
    }
}
</style>
