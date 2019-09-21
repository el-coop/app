<template>
    <div>
        <component is="style" type="text/css" v-if="uploading.length || errored.length">
            {{ uploadingCss }}
            {{ errorCss }}
        </component>
        <Chart :chartData="chartData" title="Monthly Sheets" :names="{y: 'Total'}" :uploading="uploading"
               @data-click="editPoint">
            <template #buttons>
                <div class="buttons ml-3">
                    <button class="button is-primary" @click="openSheet">Add</button>
                </div>
            </template>
        </Chart>
        <Modal :active.sync="sheetModal" @update:active="selectedSheet=null">
            <Sheet @saveSheet="addSheet" :sheet="selectedSheet"/>
        </Modal>
    </div>
</template>

<script>
	import Chart from "../../global/Chart";
	import Modal from "../../global/Modal";
	import Sheet from "./Sheet";
	import SheetObject from "../../classes/Sheet";

	export default {
		name: "CalculationSheetList",
		components: {Sheet, Chart, Modal},

		data() {
			return {
				sheetModal: false,
				selectedSheet: null,
				chartData: [],
				timezone: (new Date).getTimezoneOffset(),
				uploading: [],
				errored: [],
			};
		},

		created() {
			this.loadData();
		},

		methods: {
			async loadData() {
				const response = await axios.get('');
				this.chartData = response.data.map((sheet) => {
					return new SheetObject(sheet.date, sheet.rows, sheet.id);
				});

			},

			openSheet() {
				if (!this.selectedSheet) {
					const lastSheet = this.chartData[this.chartData.length - 1] || new SheetObject();
					this.selectedSheet = SheetObject.CreateFromOld(lastSheet);

				}
				this.sheetModal = true;
			},

			async addSheet(sheet) {
				this.sheetModal = false;
				this.selectedSheet = null;
				this.chartData.push(sheet);
				this.chartData = this.chartData.sort((a, b) => {
					return a.date.getTime() - b.date.getTime();
				});

				this.recalculateCss();

				try {
					const response = await axios.post('', {
						date: sheet.formattedDate + '-1',
						rows: sheet.rows
					});
					this.$toast.success(' ', 'Datapoint Saved');
					sheet.id = response.id;
					sheet.errors = {};
				} catch (error) {
					this.$toast.error(' ', 'Error saving datapoint');
					if (error.response && error.response.data.errors) {
						sheet.errors = this.formatErrors(error.response.data.errors);
					} else {
						sheet.errors = {
							message: 'There was a server error, please try again'
						}
					}
				}
				this.recalculateCss();
			},

			formatErrors(errorList) {
				const errors = {};
				for (let prop in errorList) {
					const parts = prop.split('.');
					console.log(parts);
					let currentPath = errors;
					do {
						let path = parts.shift();
						console.log(path, parts);

						if (!currentPath[path]) {
							if (!parts.length) {
								currentPath[path] = errorList[prop].map((value) => {
									return value.replace(prop, path);
								});
							} else {
								currentPath[path] = {};
							}
						}
						currentPath = currentPath[path];
					} while (parts.length);
				}

				console.log(errors);
				return errors;
			},

			recalculateCss() {
				this.uploading = [];
				this.errored = [];
				this.chartData.forEach((element, index) => {
					if (Object.entries(element.errors).length) {
						this.errored.push(index);
					} else if (String(element.id).indexOf('temp') > -1) {
						this.uploading.push(index);
					}
				});
			},

			editPoint(data) {
				this.selectedSheet = this.chartData[data.data.index];
				this.openSheet();
			}
		},

		computed: {
			uploadingCss() {
				let styleRule = '';
				this.uploading.forEach((index) => {
					styleRule += `.c3-circle-${index} {animation: blink 2s ease infinite;} `;
				});
				return styleRule;
			},

			errorCss() {
				let styleRule = '';
				this.errored.forEach((index) => {
					styleRule += `.c3-circle-${index} {fill: hsl(348, 100%, 61%);} `;
				});
				return styleRule;
			}

		}
	}
</script>

