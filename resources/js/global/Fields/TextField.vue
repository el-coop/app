<template>
    <div class="field" :class="{'field--is-half': options.width === 0.5}">
        <label class="label" v-if="options.label" :for="id" v-html="options.label"/>
        <input :type="options.type" :id="id" class="input" :class="{'input--border-left': options.borderLeft}" v-model="val" :placeholder="options.placeholder || ''"
               :step="options.step || 0.1"
               @focus="$emit('focus:start')"
               @blur="$emit('focus:end')"
               :name="options.name || ''" :format="options.format || 'dd/mm/yyyy'" :readonly="options.readonly || false">
        <p class="help is-muted" v-if="options.help" v-text="options.help"/>
        <p class="help is-danger" v-if="error" v-text="error"/>
    </div>
</template>

<script>
	import FieldMixin from "./FieldMixin.vue";

	export default {
		name: "TextField",
		mixins: [FieldMixin]
	}
</script>
