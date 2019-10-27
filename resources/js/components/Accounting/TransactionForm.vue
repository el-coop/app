<template>
    <div>
        <TextField v-model="value.date" :options="{
            label: 'Date',
            type: 'date',
            format: 'dd/mm/yyyy',
        }"/>
        <TextField v-model="value.label" :options="{
            label: 'Label'
        }"/>
        <TextField v-model="value.amount" :options="{
					type: 'number',
					label: 'Amount',
					forceDecimal: 2,
        }"/>
        <TextareaField v-model="value.comment" :options="{
            label: 'Comment'
        }"/>
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
			return {
				value: Object.assign(Object.create(Object.getPrototypeOf(this.transaction)), this.transaction)
			}
		},

		methods: {
			submit() {
				this.value.date = new Date(this.value.date);
				this.$emit('update', this.value);
			}
		}
	}
</script>
