<template>
    <div>
        <div class="notification is-danger" v-if="errors.message || false">
            {{ errors.message}}
        </div>
        <TextField :options="dateFieldConf" v-model="date" :error="errors.date ? errors.date[0] : null"/>
        <SheetRow v-for="(row,index) in rows" :key="row.id" @delete="removeRow(index)"
                  :errors="errors.rows && errors.rows[index] ? errors.rows[index] : {}"
                  v-model="rows[index]"/>
        <div class="message mb-1/2 is-primary is-small">
            <div class="level is-mobile message-body" @click="edit=true">
                <div class="level-left">
                    <div class="level-item">
                        <h6 class="title is-6"/>Total left:
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item" v-text="value"/>
                </div>
            </div>
        </div>
        <div class="buttons is-right">
            <button @click="rows.push({
                id: randomString()
            })" class="button">Add Row
            </button>
            <button class="button is-primary" @click="save">Save
            </button>
        </div>
    </div>
</template>

<script>
	import SheetRow from "./SheetRow";
	import TextField from "../../global/Fields/TextField";
	import Sheet from "../../classes/Sheet";

	export default {
		name: "Sheet",
		components: {TextField, SheetRow},

		props: {
			sheet: {
				type: Object,
				default() {
					return {
						rows: [],
						date: new Date(),
						errors: {}
					};
				}
			}
		},

		data() {
			return {
				date: null,
				rows: [],
				errors: {},
				dateFieldConf: {
					label: 'Date',
					type: 'month',
					format: 'mm/yyyy',
				}
			};
		},

		computed: {
			value() {
				return this.rows.reduce((total, row) => {
					let value = 0;
					switch (row.action) {
						case '+':
							value = parseFloat(row.amount);
							break;
						case '-':
							value = -parseFloat(row.amount);
							break;
					}

					return total + value;
				}, 0);
			}
		},

		methods: {
			removeRow(index) {
				this.rows.splice(index, 1);
			},

			randomString() {
				return Math.random().toString(36).substring(2, 15);
			},

			save() {
				const tempDate = new Date(`${this.date}-10`);
				const sheet = new Sheet(tempDate, this.rows, this.sheet.id);
				this.$emit('saveSheet', sheet);
			}

		},


		watch: {
			sheet: {
				handler(value) {
					this.rows = value ? value.rows : [];
					this.date = value ? value.formattedDate : '';
					this.errors = value ? value.errors : {};
				},
				immediate: true
			}
		},

	}
</script>
