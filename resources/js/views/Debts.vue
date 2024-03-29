<template>
    <div class="container" :class="{'is-loading': loading}">
        <div class="background-content" ref="backgroundContent" :class="{'background-content--small': selectedEntity}"
             :style="{height: backgroundContentHeight}">
            <DebtTable :debts="debts" :entities="entities" @update="update" @delete="destroy"
                       @invoiceSettings="invoiceSettingsModal = true"
                       :grouped-projects="groupedProjects"/>
        </div>
        <div class="foreground-content">
            <div>
                <DebtsDisplay :debts="debts"/>
            </div>
            <div>
                <DebtsPerClient :debts="debts" :entities="entities" @markBilled="markBilled"/>
            </div>
        </div>
        <Modal v-model:active="invoiceSettingsModal">
            <InvoiceSettingsModal/>
        </Modal>
    </div>
</template>

<script>

import DebtTable from "../components/Debts/DebtTable.vue";
import Debt from "../classes/Models/Debt";
import Entity from "../classes/Models/Entity";
import InteractsWithObjects from "../mixins/InteractsWithObjects";
import DebtsDisplay from "../components/Debts/DebtsDisplay.vue";
import DebtsPerClient from "../components/Debts/DebtsPerClient.vue";
import InvoiceSettingsModal from "../components/Debts/InvoiceSettingsModal.vue";
import Modal from "../global/Modal.vue";

export default {
    name: "Debts",
    components: {Modal, InvoiceSettingsModal, DebtsDisplay, DebtTable, DebtsPerClient},
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
            groupedProjects: {},
            invoiceSettingsModal: false
        };
    },

    mounted() {
        this.load();
    },

    methods: {
        async load() {
            this.loading = true;
            if (!this.entities.length) {
                this.entities = await Entity.list();
                this.groupedProjects = {};
                this.entities.forEach((entity) => {
                    this.groupedProjects[entity.id] = entity['projects'];
                });
            }
            if (!this.debts.length) {
                this.debts = await Debt.list();
                this.debts.forEach((debt) => {
                    const entity = this.getById(this.entities, debt.entity);
                    debt.entityName = entity ? entity.name : '';
                });
            }

            this.loading = false;
        },

        markBilled(debts) {
            debts.forEach((debt) => {
                debt = this.getById(this.debts, debt);
                debt.invoiced = 1;
                this.update(debt);
            });
        },

        update(debt) {
            this.updateById(this.debts, debt.id, debt);
        },


        destroy(debt) {
            this.removeById(this.debts, debt.id);
        },

    }
}
</script>

<style scoped lang="scss">
@import "node_modules/bulma/sass/utilities/initial-variables";
@import "node_modules/bulma/sass/utilities/functions";
@import "node_modules/bulma/sass/utilities/derived-variables";
@import "node_modules/bulma/sass/utilities/mixins";
@import "../../sass/variables";

.foreground-content {

    @include from($desktop) {
        display: grid;
        grid-template-columns: max-content 1fr;
        grid-template-rows: auto;
        gap: var(--gap);
    }
}
</style>
