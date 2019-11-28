<template>
    <tr class="table__row  table__row--responsive" @click="$emit('toggle')">
        <td class="table__cell table__cell--centered table__cell--narrow no-loading-overlay table__cell--action"
            :class="{'is-loading': transaction.status === 'uploading'}">
            <div class="split-buttons">
                <button class="button is-small split-buttons__button  split-buttons__button--first" @click.stop="$emit('edit')"
                        :class="{'is-success': transaction.status === 'saved', 'is-danger': transaction.status === 'error'}"
                        :disabled="transaction.status === 'deleting'"
                        v-if="transaction.status !== 'uploading'">
                    <FontAwesomeIcon :icon="icon" fixed-width/>
                </button>
                <div class="dropdown is-hidden-only-mobile">
                    <button class="button is-small split-buttons__button split-buttons__button--last">
                        <FontAwesomeIcon icon="caret-down" @click.stop="" fixed-width/>
                    </button>
                    <div class="dropdown__content">
                        <button class="button is-small is-danger" @click.stop="$emit('delete')">
                            <FontAwesomeIcon icon="trash" class="button__icon" fixed-width/>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </td>
        <td class="table__cell table__cell--narrow " data-label="Date" v-text="transaction.date.toLocaleDateString('it-IT',{
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

<style lang="scss">
    @import "~bulma/sass/utilities/initial-variables";
    @import "~bulma/sass/utilities/functions";
    @import "~bulma/sass/utilities/derived-variables";
    @import "~bulma/sass/utilities/mixins";
    @import "../../../sass/variables";

    .split-buttons {
        width: 100%;

        @include until($mobile){
            &__button {
                width: 100%;
                border-radius: var(--radius);
            }
        }
    }

</style>
