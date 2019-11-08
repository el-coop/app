<template>
	<div class="field">
        <label class="label" v-if="options.label" :for="id" v-text="options.label"/>
        <div class="select">
			<select :id="id" :name="options.name" v-model="val">
				<option v-for="(option, key) in options.options" :value="key"
						v-text="option"/>
			</select>
		</div>
		<p class="help is-danger" v-if="error" v-text="error"/>
	</div>
</template>

<script>
	import FieldMixin from "./FieldMixin";

	export default {
		name: "SelectField",
		mixins: [FieldMixin],


	}
</script>
