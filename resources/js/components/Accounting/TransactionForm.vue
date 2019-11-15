<template>
    <div>
        <TextField v-model="value.date" :options="{
            label: 'Date',
            type: 'date',
            format: 'dd/mm/yyyy',
        }" :error="errors.date ? errors.date[0] : ''"/>
        <TextField v-model="value.label" :options="{
            label: 'Label (NIS)'
        }" :error="errors.label ? errors.label[0] : ''"/>
        <TextField v-model="value.amount" :options="{
					type: 'number',
					label: 'Amount',
					forceDecimal: 2,
        }" :error="errors.amount ? errors.amount[0] : ''"/>
        <TextareaField v-model="value.comment" :options="{
            label: 'Comment'
        }" :error="errors.comment ? errors.comment[0] : ''"/>
        <button class="button is-success is-fullwidth" @click="submit">Save</button>
    </div>
</template>

<script>
	import TextField from "../../global/Fields/TextField";
	import TextareaField from "../../global/Fields/TextareaField";

	export default {
		name: "TransactionForm",
		components: {TextareaField, TextField},
		model: {
			prop: 'transaction',
			event: 'update'
		},

		props: {
			transaction: {
				required: true,
				type: Object
			},
		},

		data() {
			const transaction = Object.assign(Object.create(Object.getPrototypeOf(this.transaction)), this.transaction);
			if (!isNaN(transaction.date.getTime())) {
				transaction.date = transaction.date.toISOString().substring(0, 10);
			}
			return {
				value: transaction,
				errors: transaction.errors
			}
		},

		methods: {
			submit() {
				this.value.date = new Date(this.value.date);
				this.value.amount = parseFloat(this.value.amount);
				this.$emit('update', this.value);
			}
		}
	}
</script>
