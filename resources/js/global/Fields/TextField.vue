<template>
    <div class="field" :class="{'field--is-half': options.width === 0.5}">
        <label class="label" v-if="options.label" :for="id" v-html="options.label"/>
        <input :type="options.type" :id="id" class="input" v-model="val" :placeholder="options.placeholder || ''"
               :step="options.step || 0.1"
               :name="options.name || ''" :format="options.format || 'dd/mm/yyyy'" :readonly="options.readonly || false">
        <p class="help is-muted" v-if="options.help" v-text="options.help"/>
        <p class="help is-danger" v-if="error" v-text="error"/>
    </div>
</template>

<script>
	import FieldMixin from "./FieldMixin";

	export default {
		name: "TextField",
		mixins: [FieldMixin]
	}
</script>
