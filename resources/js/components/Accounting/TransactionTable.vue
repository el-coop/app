<template>
    <div class="card">
        <div class="card__header">
            <h5 class="title is-size-6">Transaction List</h5>
            <div>
                <button class="button is-success" @click="editTransaction()">
                    + Add
                </button>
            </div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <td class="table__cell table__cell--header table__cell--narrow">Action</td>
                <td class="table__cell table__cell--header table__cell--narrow">Date</td>
                <td class="table__cell table__cell--header table__cell--narrow">Label</td>
                <td class="table__cell table__cell--header table__cell--right">Amount</td>
            </tr>
            </thead>
            <tbody>
            <TransactionRow v-for="transaction in shownTransactions" :key="`transaction_${transaction.id}`"
                            :transaction="transaction"/>

            </tbody>
        </table>
        <div class="pagination">
            <div v-text="paginationText">
            </div>
            <div class="buttons">
                <button v-for="n in pages" v-text="n" class="button" @click="page = (n-1)" :disabled="page === (n-1)"></button>
            </div>
        </div>
        <Modal :active.sync="transactionForm" @update:active="selectedTransaction=null">
            <TransactionForm v-if="selectedTransaction" v-model="selectedTransaction" @update="updateTransaction"/>
        </Modal>
    </div>
</template>

<script>
	import Modal from "../../global/Modal";
	import TransactionForm from "./TransactionForm";
	import Transaction from "../../classes/Transaction";
	import TransactionRow from "./TransactionRow";
	import InteractsWithObjects from "../../mixins/InteractsWithObjects";

	export default {
		name: "TransactionTable",
		components: {TransactionRow, TransactionForm, Modal},
		mixins: [InteractsWithObjects],

		props: {
			transactions: {
				type: Array,
				required: true
			}
		},

		data() {
			return {
				transactionForm: false,
				selectedTransaction: null,
				pending: [],
				page: 0,
				perPage: 5
			}
		},

		computed: {
			shownTransactions() {
				return this.transactions.concat(this.pending).sort((a, b) => {
					return b.date - a.date;
				}).slice(this.pageStart, this.pageEnd);
			},

			pageStart() {
				return this.page * this.perPage;
			},

			pageEnd() {
				return Math.min(this.page * this.perPage + this.perPage, this.totalEntries);
			},

			pages() {
				return Math.ceil(this.totalEntries / this.perPage);
			},

			totalEntries() {
				return this.pending.length + this.transactions.length;
			},

			paginationText() {
				return `Showing ${this.pageStart + 1} to ${this.pageEnd} of ${this.totalEntries} entries`;
			}
		},

		methods: {
			editTransaction(transaction) {
				if (!transaction) {
					transaction = new Transaction();
				}

				this.selectedTransaction = transaction;
				this.transactionForm = true;
			},

			async updateTransaction() {
				const transaction = this.selectedTransaction;
				if (transaction.status === 'new') {
					this.pending.push(this.selectedTransaction);
				}
				this.selectedTransaction = null;
				this.transactionForm = false;
				const saved = await transaction.save();
				if (saved) {
					this.removeById(this.pending, transaction.id);
					this.$emit('update', transaction);
				}
			}
		}
	}
</script>
