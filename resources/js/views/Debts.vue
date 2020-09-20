<template>
    <div class="container" :class="{'is-loading': loading}">
        <div class="background-content" ref="backgroundContent" :class="{'background-content--small': selectedEntity}"
             :style="{height: backgroundContentHeight}">
            <transition name="fade">
                <DebtTable :debts="entities"/>
            </transition>
        </div>
    </div>
</template>

<script>

import DebtTable from "../components/Debts/DebtTable";
import Debt from "../classes/Models/Debt";

export default {
    name: "Debts",
    components: {DebtTable},
    metaInfo: {
        title: 'Debts'
    },

    data() {
        return {
            loading: true,
            backgroundContentHeight: 'auto',
            entities: [],
            selectedEntity: false
        };
    },

    mounted() {
        this.load();
    },

    methods: {
        async load() {
            this.loading = true;
            if (!this.entities.length) {
                this.entities = await Debt.list();
            }
            this.loading = false;
        }
    }
}
</script>
