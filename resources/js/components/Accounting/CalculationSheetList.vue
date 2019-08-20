<template>
    <div>
        <Chart :chartData="data" title="Monthly Sheets" :names="{y: 'Total'}">
            <template #buttons>
                <div class="buttons ml-3">
                    <button class="button is-primary" @click="addModal = true">Add</button>
                </div>
            </template>
        </Chart>
        <Modal v-model="addModal">
            <Sheet @saveSheet="addSheet"/>
        </Modal>
    </div>
</template>

<script>
	import Chart from "../../global/Chart";
	import Modal from "../../global/Modal";
	import Sheet from "./Sheet";

	export default {
		name: "CalculationSheetList",
		components: {Sheet, Chart, Modal},

		data() {
			return {
				addModal: false,
				data: [{
					x: '2018-7-1',
					y: '20000'
				}, {
					x: '2018-8-1',
					y: '10000'
				}, {
					x: '2018-9-1',
					y: '50000'
				}, {
					x: '2018-10-1',
					y: '30000'
				}, {
					x: '2018-11-1',
					y: '20000'
				},{
					x: '2018-12-1',
					y: '10000'
				}, {
					x: '2019-1-1',
					y: '10000'
				}, {
					x: '2019-2-1',
					y: '50000'
				}, {
					x: '2019-3-1',
					y: '30000'
				}, {
					x: '2019-4-1',
					y: '20000'
				}, {
					x: '2019-5-1',
					y: '10000'
				}, {
					x: '2019-6-1',
					y: '50000'
				}, {
					x: '2019-7-1',
					y: '30000'
				}]
			};
		},

		methods: {
			addSheet(sheet) {
				this.addModal = false;
				this.data.push({
					x: `${sheet.date}-1`,
					y: sheet.total,
				});
			},
		}

	}
</script>
