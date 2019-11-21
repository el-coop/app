<template>
    <Chart :chartData="chartData" title="Financial report" :names="{y: 'Total'}">
        <template #filters>
            <TextField class="field--marginless" :options="{
                type: 'date',
                format: 'dd/mm/yyyy',
            }" :error="startDateError" v-model="start"/>
            <span> - </span>
            <TextField class="field--marginless" :options="{
                type: 'date',
                format: 'dd/mm/yyyy'
             }" :error="endDateError" v-model="end"/>
            <button class="button is-primary" @click="filter">
                <FontAwesomeIcon icon="search"/>
            </button>
        </template>
    </Chart>
</template>

<script>
	import Chart from '../../global/Chart';
	import TextField from "../../global/Fields/TextField";

	export default {
		name: "TransactionsChart",
		components: {
			TextField,
			Chart
		},
		props: {
			transactions: {
				type: Array,
				required: true
			},
			startValue: {
				type: Number,
				default: 0
			},
			startDate: {
				type: String,
				required: true
			},
			endDate: {
				type: String,
				required: true
			}
		},

		data() {
			return {
				start: this.startDate,
                end: this.endDate,
				startDateError: null,
				endDateError: null,
			}
		},

		methods: {
			filter() {
				if (this.start && this.end) {
					this.startDateError = null;
					this.endDateError = null;
					return this.$emit('filter', {
						startDate: this.start,
						endDate: this.end
					});
				}

				if (!this.start) {
					this.startDateError = 'Field is required';
				}
				if (!this.end) {
					this.endDateError = 'Field is required';
				}
			}
		},

		computed: {
			chartData() {
				const transactions = this.transactions.slice(0);
				return transactions.sort((a, b) => {
					return a.date - b.date;
				}).reduce((grouped, transaction) => {
					const index = grouped.findIndex((coordinate) => {
						return coordinate.x.toDateString() === transaction.date.toDateString();
					});
					if (index > -1) {
						grouped[index].y += transaction.amount;
						grouped[index].transactions.push(transaction);
					} else {
						let amount;
						if (grouped[grouped.length - 1]) {
							amount = grouped[grouped.length - 1].y;
						} else {
							amount = this.startValue;
						}
						grouped.push({
							y: transaction.amount + amount,
							x: transaction.date,
							transactions: [transaction]
						});
					}
					return grouped;
				}, []);
			}
		}
	}
</script>
