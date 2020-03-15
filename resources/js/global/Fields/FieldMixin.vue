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
                    if (this.value instanceof Date) {
                        return this.value.toISOString().split('T')[0];
                    }
                    return this.value;
                },
                set(value) {
                    this.$emit('input', value);
                }
            }
        }
    }
</script>
