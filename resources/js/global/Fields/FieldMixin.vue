<script>
	export default {
		name: "FieldMixin",
		props: {
			value: {
				default: ''
			},
			error: {
				type: String,
				default: ''
			},
			options: {
				type: Object,
				required: true
			},
		},
		data() {
			return {
				id: `label_${this._uid}`
			}
		},

		computed: {
			val: {
				get() {
					return this.value;
				},
				set(value) {
					this.$emit('input', value);
				}
			}
		}
	}
</script>
