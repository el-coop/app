<template>
    <div class="section">
        <component is="style" type="text/css" v-if="uploading.length || errored.length">
            {{ uploadingCss }}
            {{ errorCss }}
        </component>
        <Chart :chartData="chartData" title="Monthly Sheets" :names="{y: 'Total'}" :uploading="uploading"
               @data-click="editPoint">
            <template #buttons>
                <div class="buttons ml-1">
                    <button class="button is-primary is-fullwidth" @click="openSheet">Add</button>
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
		metaInfo: {
			title: 'Accounting',
		},
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

			updateSheets(sheet) {
				const sheetIndex = this.chartData.findIndex((item) => {
					return item.id === sheet.id;
				});


				if (sheetIndex > -1) {
					sheet.errors = {};
					this.$set(this.chartData, sheetIndex, sheet);
				} else {
					this.chartData.push(sheet);
				}
				this.chartData = this.chartData.sort((a, b) => {
					return a.date.getTime() - b.date.getTime();
				});

			},

			async saveSheet(sheet) {
				if (String(sheet.id).indexOf('temp') === 0) {
					return await axios.post('', {
						date: sheet.formattedDate + '-1',
						rows: sheet.rows
					});
				}

				return await axios.patch(`${window.location.href}/${sheet.id}`, {
					date: sheet.formattedDate + '-1',
					rows: sheet.rows
				});

			},

			async addSheet(sheet) {
				this.sheetModal = false;
				this.selectedSheet = null;
				this.updateSheets(sheet);

				this.recalculateCss();
				try {
					const response = await this.saveSheet(sheet);
					this.$toast.success(' ', 'Datapoint Saved');
					sheet.id = response.data.id;
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
					let currentPath = errors;
					do {
						let path = parts.shift();

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

