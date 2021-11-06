<script>
    export default {
        name: "FieldMixin",
        props: {
            modelValue: {
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
                    if (this.modelValue instanceof Date) {
                        return this.modelValue.toISOString().split('T')[0];
                    }
                    return this.modelValue;
                },
                set(value) {
                    this.$emit('update:modelValue', value);
                }
            }
        }
    }
</script>
