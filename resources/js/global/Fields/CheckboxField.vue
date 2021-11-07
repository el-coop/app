<template>
    <div class="field">
        <label class="checkbox">
            <input type="checkbox" class="checkbox__input" :readonly="options.readonly || false" v-model="val">
            <span v-if="options.label" v-text="options.label"/>
        </label>
        <p class="help is-info" v-if="options.help" v-text="options.help"/>
        <p class="help is-danger" v-if="error" v-text="error"/>
    </div>
</template>

<script>
    import FieldMixin from "./FieldMixin";

    export default {
        name: "CheckboxField",
        mixins: [FieldMixin]
    }
</script>
