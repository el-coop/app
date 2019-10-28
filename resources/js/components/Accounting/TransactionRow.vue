<template>
    <tr class="table__row">
        <td class="table__cell table__cell--centered table__cell--narrow"
            :class="{'is-loading': transaction.status === 'uploading'}">
            <button class="button is-small" @click="$emit('edit')"
                    :class="{'is-success': transaction.status === 'saved', 'is-danger': transaction.status === 'error'}"
                    v-if="transaction.status !== 'uploading'">
                <FontAwesomeIcon :icon="icon" fixed-width/>
            </button>
        </td>
        <td class="table__cell table__cell--narrow" v-text="transaction.date.toLocaleDateString('it-IT',{
            timeZone: 'UTC',
        })"></td>
        <td class="table__cell table__cell--narrow" v-text="transaction.label"></td>
        <td class="table__cell is-nis" v-text="transaction.amount"></td>
    </tr>
</template>

<script>
	export default {
		name: "TransactionRow",

		props: {
			transaction: {
				type: Object,
				required: true
			}
		},

		computed: {
			icon() {
				if (this.transaction.status === 'error') {
					return 'exclamation';
				}

				return 'edit'
			},

		}
	}
</script>
