<template>
    <div>
        <TextField :options="dateFieldConf" v-model="date"/>
        <AccountRow v-for="(row,index) in rows" :key="row.id" @delete="removeRow(index)" v-model="rows[index]"/>
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
	import AccountRow from "./SheetRow";
	import TextField from "../../global/Fields/TextField";

	export default {
		name: "Sheet",
		components: {TextField, AccountRow},

		data() {
			return {
				rows: [],
				date: this.getCurrentDateString(),
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
						case 0:
							value = parseFloat(row.amount);
							break;
						case 1:
							value = -parseFloat(row.amount);
							break;
					}

					return total + value;
				}, 0);
			}
		},

		methods: {
			getCurrentDateString() {
				let currentDate = new Date();
				let currentDateString = `${currentDate.getFullYear()}-`;

				if (currentDate.getMonth() < 10) {
					currentDateString += `0${currentDate.getMonth() + 1}`;
				} else {
					currentDateString += currentDate.getMonth() + 1;
				}

				return currentDateString;
			},

			removeRow(index) {
				this.rows.splice(index, 1);
			},

			randomString() {
				return Math.random().toString(36).substring(2, 15);
			},

			save() {
				const tempDate = new Date(`${this.date}-10`);
				this.$emit('saveSheet', {
					date: `${tempDate.getFullYear()}-${tempDate.getMonth() + 1}`,
                    rows: this.rows,
					total: this.value,
				});
			}

		}

	}
</script>
