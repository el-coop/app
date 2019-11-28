<template>
    <div class="container" :class="{'is-loading': loading}">
        <div class="background-content">
            <TransactionsChart :transactions="transactions" :start-value="sumBefore" :entities="entities"
                               :start-date="filters.startDate" :end-date="filters.endDate" @filter="filter"/>
        </div>
        <div class="foreground-content">
            <div>
                <AccountDisplay :total="total" :transactions="transactions"/>
            </div>
            <div>
                <TransactionTable :transactions="transactions" @update="update" @delete="destroy" :entities="entities"/>
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
	import TransactionsChart from "../components/Accounting/TransactionsChart";
	import Entity from "../classes/Models/Entity";

	export default {
		name: "Accounting",
		components: {TransactionsChart, AccountDisplay, TransactionTable},
		mixins: [InteractsWithObjects],

		metaInfo: {
			title: 'Accounting'
		},

		created() {
			this.load();
		},

		data() {
			const today = new Date();
			const monthAgo = new Date();
			monthAgo.setMonth(today.getMonth() - 1);

			return {
				transactions: [],
				loading: false,
				total: 0,
				sumBefore: 0,
				filters: {
					startDate: monthAgo.toISOString().substring(0, 10),
					endDate: today.toISOString().substring(0, 10),
				},
				entities: [],
			}
		},

		methods: {
			async load() {
				this.loading = true;
				if (!this.entities.length) {
					this.entities = await Entity.list();
				}
				const response = await httpService.get(`/transactions?startDate=${this.filters.startDate}&endDate=${this.filters.endDate}`);
				if (response.status > 199 && response.status < 300) {
					const loadData = response.data;
					this.transactions = loadData.transactions.map((transaction) => {
						return new Transaction(transaction);
					});
					this.sumBefore = parseFloat(loadData.sumBefore);
					this.total = parseFloat(loadData.total);

				} else {
					if (response.status !== 401 && response.data.message !== 'Unauthenticated.') {
						this.$toast.error('Please refresh page', 'Transaction loading error');
					}
				}
				this.loading = false;

			},

			async updateTotal() {
				const response = await httpService.get('transactions/total');
				if (response.status > 199 && response.status < 300) {
					return this.total = parseFloat(response.data.total);
				}

				if (response.status !== 401 && response.data.message !== 'Unauthenticated.') {
					this.$toast.error('Please refresh page', 'Total money update error');
				}
			},

			update(transaction) {
				if (transaction.date >= new Date(`${this.filters.startDate}T00:00:00+0000`) && transaction.date <= new Date(`${this.filters.endDate}T23:59:59+0000`)) {
					this.updateById(this.transactions, transaction.id, transaction);
				} else {
					this.removeById(this.transactions, transaction.id);
				}
				this.updateTotal();
			},

			destroy(transaction) {
				this.removeById(this.transactions, transaction.id);
				this.updateTotal();
			},

			filter(filters) {
				this.filters.startDate = filters.startDate;
				this.filters.endDate = filters.endDate;
				this.load();
			}
		}
	}
</script>

<style lang="scss" scoped>
    @import "~bulma/sass/utilities/initial-variables";
    @import "~bulma/sass/utilities/functions";
    @import "~bulma/sass/utilities/derived-variables";
    @import "~bulma/sass/utilities/mixins";
    @import "../../sass/variables";

    .container {
        display: flex;
        flex-direction: column-reverse;

        @include from($mobile) {
            display: block;
        }
    }

    .foreground-content {

        @include from($desktop) {
            display: grid;
            grid-template-columns: max-content 1fr;
            grid-template-rows: auto;
            gap: var(--gap);
        }
    }
</style>
