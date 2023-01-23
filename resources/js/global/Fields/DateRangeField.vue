<template>
    <div class="date-range">
        <TextField class="field--marginless" :options="{
                type: 'date',
                format: 'dd/mm/yyyy',
            }" :error="startError" v-model="start"/>
        <span> - </span>
        <TextField class="field--marginless" :options="{
                type: 'date',
                format: 'dd/mm/yyyy'
             }" :error="endError" v-model="end"/>
    </div>
</template>

<script>

import TextField from "./TextField.vue";

export default {
    name: "DateRangeField",
    components: {
        TextField
    },
    props: {
        modelValue: {
            default() {
                return {
                    start: '',
                    end: ''
                };
            }
        },
        startError: {
            type: String,
            default: ''
        },
        endError: {
            type: String,
            default: ''
        }
    },


    computed: {
        start: {
            get() {
                if (this.modelValue.start instanceof Date) {
                    return this.modelValue.start.toISOString().split('T')[0];
                }
                return this.modelValue.start;
            },
            set(value) {
                this.$emit('update:modelValue', {
                    start: value,
                    end: this.modelValue.end
                });
            }
        },
        end: {
            get() {
                if (this.modelValue.end instanceof Date) {
                    return this.modelValue.start.toISOString().split('T')[0];
                }
                return this.modelValue.end;
            },
            set(value) {
                this.$emit('update:modelValue', {
                    start: this.modelValue.start,
                    end: value
                });
            }
        }

    }
}
</script>

<style scoped>
.date-range {
    display: flex;
    align-items: center;
}
</style>
