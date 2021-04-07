<template>
    <div>
        <slot/>
        <table class="table">
            <thead>
            <tr class="table__row table__row--header table__row--responsive">
                <td class="table__cell table__cell--header table__cell--important table__cell--narrow"/>
                <td class="table__cell table__cell--header table__cell--important">Item</td>
                <td class="table__cell table__cell--header table__cell--narrow">Quantity</td>
                <td class="table__cell table__cell--header table__cell--narrow">Rate</td>
                <td class="table__cell table__cell--header table__cell--narrow table__cell--important">Total
                </td>
                <td class="table__cell table__cell--header table__cell--narrow "/>
            </tr>
            </thead>
            <tbody>
            <tr class="table__row table__row--responsive" v-for="(item, key) in value">
                <td class="table__cell table__cell--narrow table__cell--centered">
                    <button class="button is-danger-inverted" @click="removeItem(key)">
                        <FontAwesomeIcon icon="times-circle"/>
                    </button>
                </td>
                <td class="table__cell table__cell--important">
                    <TextField :options="{borderLeft: true}" v-model="item.comment"/>
                </td>
                <td class="table__cell table__cell--narrow">
                    <TextField :options="{borderLeft: true}" v-model="item.amount"/>
                </td>
                <td class="table__cell table__cell--narrow">
                    <TextField :options="{borderLeft: true}" v-model="item.rate"/>
                </td>
                <td class="table__cell table__cell--narrow table__cell--important"
                    v-text="currency + ((item.rate || 0) * (item.amount || 0))"/>
                <td class="table__cell table__cell--narrow" title="Item currency not same as invoice currency"
                    v-if="item.currency && item.currency !== currency">
                    <font-awesome-icon class="is-danger" icon="exclamation"/>
                </td>
            </tr>
            <tr>
                <td class="table__cell table__cell--narrow table__cell--centered">
                </td>
                <td class="table__cell table__cell--important">
                    <button class="button is-info-inverted" @click="addItem">
                        + Add Item
                    </button>
                </td>
                <td class="table__cell table__cell--narrow"></td>
                <td class="table__cell table__cell--header  table__cell--narrow">Total:</td>
                <td class="table__cell table__cell--narrow">
                    <span v-text="`${currency}${itemsTotal}`"/>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
import TextField from "../../global/Fields/TextField";
import InteractsWithObjects from "../../mixins/InteractsWithObjects";

export default {
    name: 'InvoiceItems',
    components: {
        TextField
    },

    props: {
        value: {
            type: Array,
            default() {
                return [];
            }
        },
        currency: {
            type: String
        }
    },


    methods: {
        addItem() {
            this.value.push({});
        },
        removeItem(key) {
            this.value.splice(key, 1);
        }
    },

    computed: {
        itemsTotal() {
            return this.value.reduce((sum, item) => {
                return sum + ((item.rate || 0) * (item.amount || 0));
            }, 0);
        },
    }
}
</script>
