<template>
    <div class="field">
        <label class="label" v-if="options.label" :for="id" v-text="options.label"/>
        <div class="control">
			<textarea :id="id" class="textarea" v-model="val" :placeholder="options.placeholder"
                      :class="{'textarea--small' : small}"
                      @focus="$emit('focus:start')"
                      @blur="$emit('focus:end')"
                      :name="options.name"/>
            <p class="help is-info" v-if="options.help" v-text="options.help"/>
            <p class="help is-danger" v-if="error" v-text="error"/>
        </div>
    </div>
</template>

<script>
import FieldMixin from "./FieldMixin.vue";

export default {
    name: "TextareaField",
    mixins: [FieldMixin],
    props: {
        small: {
            type: Boolean,
            default: false
        }
    }
}
</script>
