<template>
    <div>
        <template v-for="field in fields">
            <component :is="field.component || 'TextField'" v-model="value[field.name]" :options="field"
                       :error="errors[field.name] ? errors[field.name][0] : ''"/>
        </template>
        <button class="button is-success is-fullwidth" @click="submit">Save</button>
    </div>
</template>

<script>
	import TextField from "../../global/Fields/TextField";
	import SelectField from "../../global/Fields/SelectField";
	import TextareaField from "../../global/Fields/TextareaField";

	export default {
		name: "EditForm",
		components: {TextareaField, TextField, SelectField},
		model: {
			prop: 'entry',
			event: 'update'
		},

		props: {
			entry: {
				required: true,
				type: Object
			},
			fields: {
				required: true,
				type: Array
			},
		},

		data() {
			const value = Object.assign(Object.create(Object.getPrototypeOf(this.entry)), this.entry);

			return {
				value,
				errors: value.errors,
			}
		},

		methods: {
			submit() {
				this.fields.forEach((field) => {
					const name = field.name;
					if (field.type === 'date') {
						this.value[name] = new Date(this.value[name]);
					} else if (field.type === 'number') {
						this.value[name] = parseFloat(this.value[name]);
					}
				});
				this.$emit('update', this.value);
			}
		}
	}
</script>
