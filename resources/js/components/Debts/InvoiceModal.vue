<template>
    <modal :active.sync="open" @update:active="$emit('close-invoicing')" :widest="true" body-class="is-marginless">
        <div class="invoice-form" v-if="invoice">
            <div class="invoice-form__column">
                <textareaField :small="true" :options="{
                    label: 'From',
                }" v-model="invoice.from" :error="invoice.errors.from ? invoice.errors.from[0] : ''"/>
                <textareaField :small="true" :options="{
                    label: 'To'
                }" v-model="invoice.to" :error="invoice.errors.to ? invoice.errors.to[0] : ''"/>

                <SelectField :options="{
                    label: 'Currency',
                    labelHelp: 'This currency option overrides all individual item currencies',
                    options: {
                        '₪': '₪',
                        '$': '$',
                        '€': '€'
                    }
                }" v-model="invoice.currency" :error="invoice.errors.currency ? invoice.errors.currency[0] : ''"/>
            </div>
            <div class="invoice-form__column">
                <TextField :options="{
                    label: 'Invoice Number',
                }" v-model="invoice.invoiceNumber"
                           :error="invoice.errors.invoiceNumber ? invoice.errors.invoiceNumber[0] : ''"/>
                <TextField :options="{
                    label: 'Date',
                    type: 'date',
                    format: 'dd/mm/yyyy'
                }" v-model="invoice.date" :error="invoice.errors.date ? invoice.errors.date[0] : ''"/>
                <TextField :options="{
                    label: 'Due Date',
                    type: 'date',
                    format: 'dd/mm/yyyy'
                }" v-model="invoice.dueDate"
                           :error="invoice.errors.dueDate ? invoice.errors.dueDate[0] : ''"/>
            </div>
            <InvoiceItems v-if="open" v-model="invoice.items"
                          class="invoice-form__column invoice-form__column--items"
                          :currency="invoice.currency">
                <hr>
            </InvoiceItems>
            <div class="invoice-form__column invoice-form__column--notes">
                <hr>
                <TextareaField :small="true" :options="{
                    label: 'Notes'
                }" v-model="invoice.notes"
                               :error="invoice.errors.notes ? invoice.errors.notes[0] : ''"/>
            </div>
            <div>
                <CheckboxField :options="{
                label: 'Mark as billed'
            }" v-model="invoice.markBilled" :error="invoice.errors.markBilled ? invoice.errors.markBilled[0] : ''"/>
                <button class="button is-success" @click="generateInvoice" :class="{'is-loading': generatingInvoice}"
                        :disabled="generatingSmartechEmail">
                    Generate Invoice
                </button>
                <button class="button is-info-inverted" :class="{'is-loading': generatingSmartechEmail}"
                        :disabled="generatingInvoice" @click="generateSmartechEmail">Send Smartech Email
                </button>
            </div>
        </div>
    </modal>
</template>

<script>
import Modal from "../../global/Modal";
import TextareaField from "../../global/Fields/TextareaField";
import TextField from "../../global/Fields/TextField";
import InvoiceItems from "./InvoiceItems";
import CheckboxField from "../../global/Fields/CheckboxField";
import SelectField from "../../global/Fields/SelectField";
import Invoice from "../../classes/Models/Invoice";

export default {
    name: "InvoiceModal",
    components: {SelectField, InvoiceItems, TextField, TextareaField, Modal, CheckboxField},

    props: {
        debtList: {
            type: Object,

        }
    },

    data() {
        return {
            open: false,
            generatingInvoice: false,
            invoice: null,
            generatingSmartechEmail: false
        };
    },

    methods: {
        async generateInvoice() {
            this.generatingInvoice = true;

            const response = await this.invoice.generate();

            this.generatingInvoice = false;

            if (response.status > 199 && response.status < 300) {
                const blob = new Blob([response.data], {type: 'application/pdf'});
                const blobURL = window.URL.createObjectURL(blob);
                const tempLink = document.createElement('a');
                tempLink.style.display = 'none';
                tempLink.href = blobURL;
                tempLink.setAttribute('download', 'invoice.pdf');

                document.body.appendChild(tempLink);
                tempLink.click();
                document.body.removeChild(tempLink);
                window.URL.revokeObjectURL(blobURL);

                if (this.invoice.markBilled) {
                    this.markBilled();
                    this.open = false;
                }
            } else {
                this.$toast.error('Please try again', 'Invoice generation failed')
            }

        },

        markBilled() {
            const debts = this.invoice.items.reduce((debts, item) => {
                if (item.debts) {
                    item.debts.forEach((debt) => {
                        debts.push(debt);
                    })
                }
                return debts;
            }, []);
            this.$emit('markBilled', debts);
        },

        async generateSmartechEmail() {
            this.generatingSmartechEmail = true;

            const success = await this.invoice.generateSmartechEmail();

            this.generatingSmartechEmail = false;
            if (success) {
                this.$toast.success('Check your ELCOOP email', 'Email sent');
                if (this.invoice.markBilled) {
                    this.markBilled();
                    this.open = false;
                }
            } else {
                this.$toast.error('Please try again', 'Email failed')
            }
        }

    },

    watch: {
        debtList() {
            if (this.debtList) {
                this.open = true;
                this.invoice = new Invoice(this.debtList.items);
            }
        }
    }
}
</script>

<style lang="scss" scoped="true">
@import "~bulma/sass/utilities/initial-variables";
@import "~bulma/sass/utilities/functions";
@import "~bulma/sass/utilities/derived-variables";
@import "~bulma/sass/utilities/mixins";
@import "../../../sass/variables";

.invoice-form {
    display: block;

    @include from($desktop) {
        display: grid;
        grid-template-columns: repeat(2, 47%);
        grid-template-areas:
        "info dates"
        "items items"
        "notes notes"
        "end end";
        grid-column-gap: 6%;
    }

    &__column {

        margin-bottom: 0.75rem;

        &--items {
            grid-area: items;
        }

        &--notes {
            grid-area: notes;
        }
    }
}
</style>
