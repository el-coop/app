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

import TextField from "./TextField";

export default {
    name: "DateRangeField",
    components: {
        TextField
    },
    props: {
        value: {
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
                if (this.value.start instanceof Date) {
                    return this.value.start.toISOString().split('T')[0];
                }
                return this.value.start;
            },
            set(value) {
                this.$emit('input', {
                    start: value,
                    end: this.value.end
                });
            }
        },
        end: {
            get() {
                if (this.value.end instanceof Date) {
                    return this.value.start.toISOString().split('T')[0];
                }
                return this.value.end;
            },
            set(value) {
                this.$emit('input', {
                    start: this.value.start,
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
}
</style>
