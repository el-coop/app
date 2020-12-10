<template>
    <tr class="table__row  table__row--responsive">
        <td class="table__cell table__cell--centered table__cell--narrow no-loading-overlay table__cell--action"
            :class="{'is-loading': debt.status === 'uploading' || debt.status === 'deleting'}">
            <SplitActionButtons :entry="debt" @edit="$emit('edit')" @delete="$emit('delete')">
                <button class="button is-small is-info dropdown__content-button" @click.stop="$emit('toggle-billed')" v-text="debt.invoiced ? 'Not billed' : 'Billed'"/>
            </SplitActionButtons>
        </td>
        <td class="table__cell table__cell--narrow" data-label="Date" v-text="debt.date.toLocaleDateString('en-GB',{
            timeZone: 'UTC',
        })"/>
        <td class="table__cell table__cell--narrow table__cell--centered" data-label="Billed">
            <FontAwesomeIcon :icon="debt.invoiced ? 'check' : 'times-circle'"
                             :class="debt.invoiced ? 'is-success' : 'is-danger'"/>
        </td>
        <td class="table__cell table__cell--important" v-text="debt.entityName"/>
        <td class="table__cell">
            <input :value="debt.comment" class="input" readonly>
        </td>
        <td class="table__cell table__cell--right table__cell--important table__cell--success"
            v-text="`${debt.currency}${debt.totalAmount}`"></td>
    </tr>
</template>

<script>
import SplitActionButtons from "../../global/Table/SplitActionButtons";
import InteractsWithObjects from "../../mixins/InteractsWithObjects";

export default {
    name: "DebtRow",
    components: {SplitActionButtons},
    mixins: [InteractsWithObjects],

    props: {
        debt: {
            type: Object,
            required: true
        },
        withDelete: {
            type: Boolean,
            default: false
        },
        entities: {
            type: Array,
            required: true
        },
    },


    computed: {
        icon() {
            if (this.entity.status === 'error') {
                return 'exclamation';
            }

            return 'edit'
        },

    }
}
</script>
