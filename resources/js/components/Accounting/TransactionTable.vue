<template>
    <div class="card">
        <div class="card__header">
            <div>
                <h5 class="title is-size-6">Transaction List</h5>
                <SelectField v-model="perPage" :options="{
                 label:'Per page',
                name: 'per-page',
                options: {
                5: '5',
                10: '10',
                20: '20',
                }
                }"/>
            </div>
            <div>
                <button class="button is-success" @click="editTransaction()">
                    + Add
                </button>
            </div>
        </div>
        <template v-for="table in [pending, shownTransactions]">
            <template v-if="table.length">
                <table class="table">
                    <thead>
                    <tr class="table__row table__row--header table__row--responsive">
                        <td class="table__cell table__cell--header table__cell--narrow">Action</td>
                        <td class="table__cell table__cell--header table__cell--narrow">Date</td>
                        <td class="table__cell table__cell--header table__cell--narrow table__cell--important">Label
                        </td>
                        <td class="table__cell table__cell--header table__cell--right table__cell table__cell--important">
                            Amount
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <TransactionRow v-for="(transaction, index) in table" :key="`transaction_${transaction.id}`"
                                    :class="{'table__row--responsive--active': active === index}"
                                    @toggle="toggleActive(index)"
                                    @edit="editTransaction(transaction)"
                                    @delete="deleteTransaction(transaction)"
                                    :transaction="transaction"/>
                    </tbody>
                </table>
                <hr>
            </template>
        </template>
        <div class="pagination">
            <div v-text="paginationText">
            </div>
            <div class="buttons">
                <button v-for="n in pages" v-text="n" class="button" @click="page = (n-1)"
                        :disabled="page === (n-1)"></button>
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
	import SelectField from "../../global/Fields/SelectField";

	export default {
		name: "TransactionTable",
		components: {SelectField, TransactionRow, TransactionForm, Modal},
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
				perPage: localStorage.getItem('accounting-per-page') || 5,
				active: null
			}
		},

		computed: {
			shownTransactions() {
				return this.transactions.sort((a, b) => {
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
			toggleActive(index) {
				if (this.active === index) {
					return this.active = null;
				}
				this.active = index;
			},
			editTransaction(transaction) {
				if (!transaction) {
					transaction = new Transaction();
				}

				this.selectedTransaction = transaction;
				this.transactionForm = true;
			},

			async deleteTransaction(transaction) {
				const response = await transaction.delete();

				if (response) {
					this.$emit('delete', transaction);
					this.$toast.success(' ', 'Transaction deleted');
					return;
				}
				this.$toast.error('Please try again', 'Transaction delete error');

			},

			async updateTransaction() {
				const transaction = this.selectedTransaction;
				this.updateById(this.pending, transaction.id, transaction);
				this.selectedTransaction = null;
				this.transactionForm = false;
				const response = await transaction.save();
				if (transaction.status === 'saved') {
					this.removeById(this.pending, transaction.id);
					this.$emit('update', transaction);
					this.$toast.success(' ', 'Transaction saved');
				} else {
					if (response.status !== 401 && response.data.message !== 'Unauthenticated.') {
						this.$toast.error('Please try again', 'Transaction save error');
					}
				}
			}
		},

		watch: {
			async perPage(value) {
				localStorage.setItem('accounting-per-page', value);
			}
		}
	}
</script>
