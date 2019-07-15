<template>
	<div class="buttons">
		<button v-for="(color, theme) in themes" class="button is-rounded theme-picker__button"
				:style="{ 'background-color' : color}" @click="switchTheme(theme)">
		</button>
	</div>
</template>

<script>
	export default {
		name: "ThemePicker",

		data() {
			return {
				themes: {
					light: '#f7f7f7',
					dark: '#18181e'
				}
			}
		},

		created() {
			this.switchTheme(localStorage.getItem('theme') || 'light');
		},

		methods: {
			switchTheme(theme) {
				this.$bus.$emit('theme-switch', theme);
				localStorage.setItem('theme', theme);
			}
		}
	}
</script>

<style lang="scss" scoped>
	.theme-picker {
		&__button {
			height: 1.1em;
			width: 1.1em;
			padding: 0;
		}
	}
</style>
