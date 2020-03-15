<template>
    <tr class="table__row  table__row--responsive" @click="$emit('toggle')">
        <td class="table__cell table__cell--centered table__cell--narrow no-loading-overlay table__cell--action"
            :class="{'is-loading': transaction.status === 'uploading' || transaction.status === 'deleting'}">
            <SplitActionButtons :entry="transaction" @edit="$emit('edit')" @delete="$emit('delete')"/>
        </td>
        <td class="table__cell table__cell--narrow " data-label="Date" v-text="transaction.date.toLocaleDateString('en-GB',{
            timeZone: 'UTC',
        })"/>
        <td class="table__cell table__cell--important" v-text="entityName"/>
        <div class="table__cell" data-label="Reason" v-text="transaction.reason"/>
        <td class="table__cell is-nis table__cell--right table__cell--important"
            :class="{'table__cell--danger': transaction.amount < 0,'table__cell--success': transaction.amount > 0}"
            v-text="(transaction.amount * (transaction.rate || 1)).toFixed(2)"></td>
        <td class="table__cell table__cell--right table__cell--action is-hidden-tablet">
            <button class="button is-small is-danger" :class="{ 'is-loading': transaction.status === 'deleting'}"
                    @click.stop="$emit('delete')">
                <FontAwesomeIcon icon="trash" fixed-width/>
            </button>
        </td>
    </tr>
</template>

<script>
    import InteractsWithObjects from "../../mixins/InteractsWithObjects";
    import SplitActionButtons from "../../global/Table/SplitActionButtons";

    export default {
        name: "TransactionRow",
        components: {SplitActionButtons},
        mixins: [InteractsWithObjects],

        props: {
            transaction: {
                type: Object,
                required: true
            },
            entities: {
                type: Array,
                required: true
            },

        },

        computed: {
            entityName() {
                if (!this.transaction.entity) {
                    return '';
                }
                const entity = this.getById(this.entities, this.transaction.entity);
                return entity ? entity.name : '';
            },
            icon() {
                if (this.transaction.status === 'error') {
                    return 'exclamation';
                }

                return 'edit'
            },
        }
    }
</script>
