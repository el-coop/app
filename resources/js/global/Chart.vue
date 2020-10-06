<template>
    <div class="chart">
        <div class="chart__top-row">
            <div class="buttons chart__pagination">
                <button class="button" @click="changePage(1)"
                        :disabled="chartData.length - breakpoints[breakpoint] - position <= 0 || loading">Previous
                </button>
                <button class="button" @click="changePage(-1)" :disabled="position === 0 || loading">
                    Next
                </button>
            </div>
            <div class="chart__filters" v-if="$slots.filters">
                <slot name="filters"/>
            </div>

        </div>
        <div ref="chart"></div>
    </div>
</template>

<script>
	import Chart from '../classes/Chart';

	export default {
		name: "Chart",
		props: {
			chartData: {
				type: Array,
				required: true
			},
			show: {
				type: Number,
				default: 10
			},
			title: {
				type: String,
				default: ''
			},
			keys: {
				type: Object,
				default() {
					return {
						x: 'x',
						value: ['y']
					}
				}
			},
			names: {
				type: Object,
				default() {
					return {
						y: 'y',
					}
				}
			},
			onClick: {
				type: Function,
			},
		},

		data() {
			return {
				breakpoint: 1200,
				chart: null,
				breakpoints: {
					0: Math.round(this.show / 4),
					576: Math.round(this.show / 3),
					769: Math.round(this.show * 2 / 3),
					1200: this.show,
				},
				position: 0,
				loading: false
			}
		},

		mounted() {
			this.calculateBreakpoint();

			this.chart = new Chart(this.$refs.chart, {
				data: this.displayed,
				keys: this.keys,
				names: this.names
			}, {
				title: this.title,
				onResized: this.calculateBreakpoint,
				onRendered: () => {
					setTimeout(() => {
						this.loading = false;
					}, 300);
				},
				onClick: this.onClick || this.clickFunction
			});
		},

		methods: {
			calculateBreakpoint() {
				let breakpoint = false;
				const keys = Object.keys(this.breakpoints);

				for (let i = (keys.length - 1); i > -1 && !breakpoint; i--) {
					if (window.innerWidth > keys[i]) {
						breakpoint = keys[i];
					}
				}

				this.breakpoint = breakpoint;
			},

			clickFunction(data, element) {
				this.$emit('data-click', {
					data,
					element
				})
			},

			changePage(value) {
				this.position += value;
				this.loading = true;
			}
		},

		computed: {
			displayed() {
				if (this.chartData.length - this.breakpoints[this.breakpoint] - this.position < 0) {
					this.position = 0;
				}
				return this.chartData.slice(Math.max(this.chartData.length - this.breakpoints[this.breakpoint] - this.position, 0), this.chartData.length - this.position);
			}
		},

		watch: {
			displayed(value) {
				this.chart.load(value);
			},
			chartData() {
				this.position = 0;
			}
		}
	}
</script>
