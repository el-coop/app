<template>
    <div>
        <template v-for="field in computedFields">
            <component :is="field.component || 'TextField'" v-model="value[field.name]" :options="field"
                       :error="errors[field.name] ? errors[field.name][0] : ''" v-show="show(field)"
                       @focus:start="$emit('focus:start')"
                       @focus:end="$emit('focus:end')"/>
        </template>
        <button class="button is-success is-fullwidth" @click="submit">Save</button>
    </div>
</template>

<script>
import TextField from "../../global/Fields/TextField";
import SelectField from "../../global/Fields/SelectField";
import TextareaField from "../../global/Fields/TextareaField";
import MultiFileField from "../Fields/MultiFileField";
import FileArrayField from "../Fields/FileArrayField";

export default {
    name: "EditForm",
    components: {TextareaField, TextField, SelectField, MultiFileField, FileArrayField},

    props: {
        entry: {
            required: true,
            type: Object
        },
        fields: {
            required: true,
            type: Array
        },
    },

    data() {
        const value = Object.assign(Object.create(Object.getPrototypeOf(this.entry)), this.entry);

        return {
            value,
            errors: value.errors,
            computedFields: {},
            hasFunctions: true
        }
    },

    methods: {
        show(field) {

            if (! field.hasOwnProperty('show')) {
                return true;
            }
            if(typeof field.show !== "function"){
                return field.show;
            }
            return field.show(this.value);
        },
        submit() {
            this.fields.forEach((field) => {
                const name = field.name;
                if (field.type === 'date') {
                    this.value[name] = new Date(this.value[name]);
                } else if (field.type === 'number' && this.value[name]) {
                    this.value[name] = parseFloat(this.value[name]);
                }
            });
            this.$emit('update:entry', this.value);
        }
    },

    watch: {
        value: {
            handler() {
                if (!this.hasFunctions) {
                    return this.fields;
                }

                this.hasFunctions = false;
                this.computedFields = this.fields.map((field) => {
                    const result = {};
                    Object.assign(result, field);
                    for (let key in field) {

                        const option = field[key];
                        if (key !== 'show' && option instanceof Function) {
                            this.hasFunctions = true;
                            result[key] = option(field, this.value, this);
                        }
                    }

                    return result;
                });
            },
            deep: true,
            immediate: true
        }
    }
}
</script>
