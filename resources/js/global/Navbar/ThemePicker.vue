<template>
    <div class="theme-picker">
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

		methods: {
			switchTheme(theme) {
				this.$store.commit('switchTheme', theme);
			}
		}
	}
</script>

<style lang="scss" scoped>
    .theme-picker {
        flex-direction: row;
        justify-self: end;
        display: flex;
        align-items: center;
        justify-content: flex-start;

        &__button {
            height: 1.1em;
            width: 1.1em;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            padding: 0;

            &:hover {
                border: 1px solid var(--primary-color);
            }
        }
    }
</style>
