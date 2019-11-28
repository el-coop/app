<template>
    <tr class="table__row  table__row--responsive" @click="$emit('toggle')">
        <td class="table__cell table__cell--centered table__cell--narrow no-loading-overlay table__cell--action"
            :class="{'is-loading': transaction.status === 'uploading'}">
            <button class="button is-small" @click.stop="$emit('edit')"
                    :class="{'is-success': transaction.status === 'saved', 'is-danger': transaction.status === 'error'}"
                    :disabled="transaction.status === 'deleting'"
                    v-if="transaction.status !== 'uploading'">
                <FontAwesomeIcon :icon="icon" fixed-width/>
            </button>
        </td>
        <td class="table__cell table__cell--narrow " data-label="Date" v-text="transaction.date.toLocaleDateString('it-IT',{
            timeZone: 'UTC',
        })"></td>
        <td class="table__cell table__cell--important" v-text="entityName"></td>
        <div class="table__cell" data-label="Reason" v-text="transaction.reason"></div>
        <td class="table__cell is-nis table__cell--right table__cell--important"
            :class="{'table__cell--danger': transaction.amount < 0,'table__cell--success': transaction.amount > 0}"
            v-text="(transaction.amount * (transaction.rate || 1)).toFixed(2)"></td>
        <td class="table__cell table__cell--right table__cell--action">
            <button class="button is-small is-danger" :class="{ 'is-loading': transaction.status === 'deleting'}"
                    @click.stop="$emit('delete')">
                <FontAwesomeIcon icon="trash" fixed-width/>
            </button>
        </td>
    </tr>
</template>

<script>
	import InteractsWithObjects from "../../mixins/InteractsWithObjects";

	export default {
		name: "TransactionRow",
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
