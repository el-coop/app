<template>
    <div class="container" :class="{'is-loading': loading}">
        <div class="background-content" style="height: 400px">
        </div>
        <div class="foreground-content">
            <div>
                <AccountDisplay :total="total" :table-numbers="tableNumbers"/>
            </div>
            <div>
                <TransactionTable :transactions="transactions" @update="update"/>
            </div>
        </div>
    </div>
</template>

<script>
	import TransactionTable from "../components/Accounting/TransactionTable";
	import InteractsWithObjects from "../mixins/InteractsWithObjects";
	import AccountDisplay from "../components/Accounting/AccountDisplay";
	import httpService from "../classes/HttpService";
	import Transaction from "../classes/Transaction";

	export default {
		name: "Accounting",
		components: {AccountDisplay, TransactionTable},
		mixins: [InteractsWithObjects],

		metaInfo: {
			title: 'Accounting'
		},

		created() {
			this.initialLoad();
		},

		data() {
			return {
				transactions: [],
				loading: false,
				total: 0,
				tableNumbers: {
					total: 0,
					income: 0,
					expenditure: 0
				},
			}
		},

		methods: {
			async initialLoad() {
				this.loading = true;
				const response = await httpService.get('/transactions');
				if (response.status > 199 && response.status < 300) {
					const loadData = response.data;
					this.transactions = loadData.transactions.map((transaction) => {
						this.tableNumbers.total += transaction.amount;
						if (transaction.amount > 0) {
							this.tableNumbers.income += transaction.amount;
						} else {
							this.tableNumbers.expenditure += transaction.amount;
						}
						return new Transaction(transaction);
					});
					this.total = parseFloat(loadData.total);

				} else {
					if (response.status !== 401 && response.data.message !== 'Unauthenticated.') {
						this.$toast.error('Please refresh page', 'Transaction loading error');
					}
				}
				this.loading = false;

			},

			async update(transaction) {
				this.updateById(this.transactions, transaction.id, transaction);
				const response = await httpService.get('transactions/total');
				if (response.status > 199 && response.status < 300) {
					return this.total = parseFloat(response.data.total);
				}

				if (response.status !== 401 && response.data.message !== 'Unauthenticated.') {
					this.$toast.error('Please refresh page', 'Total money update error');
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
    @import "~bulma/sass/utilities/initial-variables";
    @import "~bulma/sass/utilities/functions";
    @import "~bulma/sass/utilities/derived-variables";
    @import "~bulma/sass/utilities/mixins";

    .foreground-content {

        @include from($tablet) {
            display: grid;
            grid-template-columns: max-content 1fr;
            grid-template-rows: auto;
            gap: var(--gap);
        }
    }
</style>
