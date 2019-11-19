<template>
    <div class="card">
        <div class="card__header">
            <h5 class="title is-size-6">Total Money</h5>
        </div>
        <div class="title is-size-1 has-text-centered"><span class="is-size-5 is-nis"/><span
            v-text="shownTotal.toFixed(2)"/>
        </div>
        <table class="table">
            <tbody>
            <tr class="table__row">
                <td class="table__cell">Table total</td>
                <td class="table__cell is-nis table__cell--right" v-text="transactionsTotal.toFixed(2)"/>
            </tr>
            <tr class="table__row">
                <td class="table__cell">Table income</td>
                <td class="table__cell is-nis table__cell--right" v-text="income.toFixed(2)"/>
            </tr>
            <tr class="table__row">
                <td class="table__cell">Table expenditure</td>
                <td class="table__cell is-nis table__cell--right" v-text="expenditure.toFixed(2)"/>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
	export default {
		name: "AccountDisplay",

		props: {
			total: {
				required: true,
				type: Number
			},
			transactions: {
				required: true,
				type: Array
			}
		},

		data() {
			return {
				shownTotal: this.total,
				expenditure: 0,
				income: 0
			}
		},

		computed: {
			transactionsTotal() {
				this.expenditure = 0;
				this.income = 0;
				return this.transactions.reduce((sum, transaction) => {
					if (transaction.amount > 0) {
						this.income += transaction.amount;
					} else {
						this.expenditure += transaction.amount;
					}
					return sum += transaction.amount;
				}, 0);
			}
		},

		methods: {
			increaseTotalBySteps(newValue, step) {

				setTimeout(() => {
					this.shownTotal += step;
					if (this.shownTotal.toFixed(2) !== newValue.toFixed(2)) {
						setTimeout(this.increaseTotalBySteps(newValue, step));
					}
				}, 50);
			}
		},

		watch: {
			total(newValue) {
				const step = (newValue - this.shownTotal) / 10;

				this.increaseTotalBySteps(newValue, step);
			}
		}
	}
</script>
