<template>
    <div class="message mb-1/2 is-small" :class="messageClass">
        <Modal :active.sync="edit">
            <component v-for="(field,index) in fields" :key="index" :is="field.component" :options="field"
                       v-model="actualValue[field.name]" :error="errors[field.name] ? errors[field.name][0] : null"/>
            <div class="buttons">
                <button class="button is-primary is-flex-1" @click="save">Save</button>
                <button class="button is-danger" @click="remove">Remove</button>
            </div>
        </Modal>
        <div class="level is-mobile message-body" @click="edit=true">
            <div class="level-left">
                <div class="level-item is-block">
                    <h6 class="title is-6 is-spaced" v-text="actualValue.label || 'Click to edit'"/>
                    <h6 class="subtitle is-6" v-if="actualValue.comment" v-text="actualValue.comment"/>
                </div>
            </div>
            <div class="level-right">
                <div class="level-item" v-text="actualValue.amount || ''"/>
            </div>
        </div>
    </div>
</template>

<script>
	import Modal from "../../global/Modal";
	import TextField from "../../global/Fields/TextField";
	import SelectField from "../../global/Fields/SelectField";
	import TextareaField from "../../global/Fields/TextareaField";

	export default {
		name: "SheetRow",
		components: {Modal, TextField, SelectField, TextareaField},

		props: {
			value: {
				type: Object,
				default() {
					return {};
				}
			},
            errors: {
				type: Object,
				default() {
					return {};
				}
            }
		},

		data() {
			return {
				edit: false,
				actualValue: this.value,
				fields: [{
					component: 'TextField',
					label: 'Label',
					name: 'label',
				}, {
					component: 'TextField',
					type: 'number',
					label: 'Amount',
					forceDecimal: 2,
					name: 'amount',
				}, {
					component: 'SelectField',
					label: 'Action',
					options: {
						'+' : '+', '-':'-', header:'Header', ignore: 'Ignore'
					},
					name: 'action',
				}, {
					component: 'TextareaField',
					label: 'Comment',
					name: 'comment',
				}]
			}
		},

		methods: {
			save() {
				this.edit = false;
				this.$emit('input', this.actualValue);
			},
			remove() {
				this.edit = false;
				this.$emit('delete');
			}
		},

		computed: {
			messageClass() {
				switch (this.value['action']) {
					case '+':
						return 'is-success';
					case '-':
						return 'is-danger';
					case 'header':
						return 'is-info';
					default:
						return 'is-dark';
				}
			}
		}
	}
</script>
