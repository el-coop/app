<template>
    <div :class="{'is-loading' : loading}">
        <TextareaField :options="{
            label: 'Default From Field'
        }" v-model="invoiceSettings.from"/>

        <TextField :options="{
            label: 'Next Invoice Number'
        }" v-model="invoiceSettings.nextInvoice"/>
        <button class="button is-success is-fullwidth" :class="{'is-loading': saving}" @click="save">
            Save
        </button>
    </div>
</template>

<script>
import TextareaField from "../../global/Fields/TextareaField.vue";
import TextField from "../../global/Fields/TextField.vue";
import InvoiceItems from "./InvoiceItems.vue";
import CheckboxField from "../../global/Fields/CheckboxField.vue";
import SelectField from "../../global/Fields/SelectField.vue";
import FormException from "../../classes/FormException";

export default {
    name: "InvoiceSettingsModal",
    components: {SelectField, InvoiceItems, TextField, TextareaField, CheckboxField},

    data() {
        return {
            open: false,
            invoiceSettings: {},
            saving: false,
            loading: true
        };
    },

    async created() {
        await this.loadInvoiceSettings()
    },

    methods: {
        async loadInvoiceSettings() {
            this.invoiceSettings = await this.$store.dispatch('User/getInvoiceSettings') || {};
            this.loading = false;
        },

        async save() {
            this.saving = true;
            this.errors = [];

            try {
                await this.$store.dispatch('User/saveInvoiceSettings', {
                    ...this.invoiceSettings
                })
                this.$toast.success(' ', 'Settings updated');

            } catch (exception) {
                if (exception instanceof FormException) {
                    this.errors = exception.validationErrors;
                    this.$toast.error('Please try again', 'Settings update validation error');
                } else {
                    this.$toast.error('Please try again', 'Settings update error');
                }
            }

            this.saving = false;
        }
    },

}
</script>
