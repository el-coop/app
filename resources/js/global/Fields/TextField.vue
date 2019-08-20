<template>
    <div class="field">
        <label class="label" v-if="options.label" :for="id" v-text="options.label"/>
        <div class="control">
            <input :type="options.type" :id="id" class="input" v-model="val" :placeholder="options.placeholder || ''" :step="options.step || 0.1"
                   :name="options.name || ''" :format="options.format || 'dd/mm/yyyy'">
            <p class="help is-danger" v-if="error" v-text="error"/>
        </div>
    </div>
</template>

<script>
	import FieldMixin from "./FieldMixin";

	export default {
		name: "TextField",
		mixins: [FieldMixin]
	}
</script>
