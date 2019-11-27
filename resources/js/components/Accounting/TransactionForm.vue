<template>
    <div>
        <template v-for="field in fields">
            <component :is="field.component || 'TextField'" v-model="value[field.name]" :options="field"
                       :error="errors[field.name] ? errors[field.name][0] : ''"></component>
        </template>
        <button class="button is-success is-fullwidth" @click="submit">Save</button>
    </div>
</template>

<script>
	import TextField from "../../global/Fields/TextField";
	import SelectField from "../../global/Fields/SelectField";
	import TextareaField from "../../global/Fields/TextareaField";
	import Transaction from "../../classes/Transaction";
	import Entity from "../../classes/Entity";

	export default {
		name: "TransactionForm",
		components: {TextareaField, TextField, SelectField},
		model: {
			prop: 'transaction',
			event: 'update'
		},

		props: {
			transaction: {
				required: true,
				type: Object
			},
			entities: {
				required: true,
				type: Array
			},
		},

		data() {
			const transaction = Object.assign(Object.create(Object.getPrototypeOf(this.transaction)), this.transaction);
			if (!isNaN(transaction.date.getTime())) {
				transaction.date = transaction.date.toISOString().substring(0, 10);
			}
			const entityOptions = {};
			this.entities.forEach((value) => {
				entityOptions[value.id] = value.name;
			});

			const fields = Transaction.fields();
			const field = fields.find((fieldOptions) => {
				return fieldOptions.name === 'entity';
			});

			field.options = entityOptions;

			return {
				value: transaction,
				errors: transaction.errors,
				fields
			}
		},

		methods: {
			capitalizeFirst(str) {
				return str.charAt(0).toUpperCase() + str.slice(1);
			},
			submit() {
				this.fields.forEach((field) => {
					const name = field.name;
					if (field.type === 'date') {
						this.value[name] = new Date(this.value[name]);
					} else if(field.type === 'number'){
						this.value[name] = parseFloat(this.value[name]);
					}
				});
				this.$emit('update', this.value);
			}
		}
	}
</script>
