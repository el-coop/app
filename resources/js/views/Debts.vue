<template>
    <div class="container" :class="{'is-loading': loading}">
        <div class="background-content" ref="backgroundContent" :class="{'background-content--small': selectedEntity}"
             :style="{height: backgroundContentHeight}">
            <transition name="fade">
                <DebtTable :debts="debts" :entities="entities" @update="update" @delete="destroy"
                           :grouped-projects="groupedProjects"/>
            </transition>
        </div>
    </div>
</template>

<script>

import DebtTable from "../components/Debts/DebtTable";
import Debt from "../classes/Models/Debt";
import Entity from "../classes/Models/Entity";
import InteractsWithObjects from "../mixins/InteractsWithObjects";

export default {
    name: "Debts",
    components: {DebtTable},
    mixins: [InteractsWithObjects],

    metaInfo: {
        title: 'Debts'
    },

    data() {
        return {
            loading: true,
            backgroundContentHeight: 'auto',
            debts: [],
            selectedEntity: false,
            entities: [],
            groupedProjects: {}
        };
    },

    mounted() {
        this.load();
    },

    methods: {
        async load() {
            this.loading = true;
            if (!this.debts.length) {
                this.debts = await Debt.list();
            }
            if (!this.entities.length) {
                this.entities = await Entity.list();
                this.groupedProjects = {};
                this.entities.forEach((entity) => {
                    this.groupedProjects[entity.id] = entity['projects'];
                });
            }

            this.loading = false;
        },

        update(debt) {
            this.updateById(this.debts, debt.id, debt);
        },


        destroy(debt) {
            this.removeById(this.debts, debt.id);
        }
    }
}
</script>
