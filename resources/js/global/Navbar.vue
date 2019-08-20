<template>
	<nav class="navbar has-shadow is-spaced">
		<div class="container">
			<div class="navbar-brand justify-content-between-touch">
				<RouterLink to="/" class="navbar-item">
					<div class="is-size-3-desktop is-size-4-touch">
						El-Coop
					</div>
				</RouterLink>
				<ThemePicker class="navbar-item"/>
			</div>
			<div class="navbar-menu is-active">
				<div class="navbar-end">
					<RouterLink v-for="route in routes" :to="route.path" class="navbar-menu__button button"
								:key="route.name"
								tag="button">
						<FontAwesomeIcon v-if="icons[route.name]" :icon="icons[route.name]"/>
						<span class="has-text-6-mobile" v-text="route.name"/>
					</RouterLink>
				</div>
			</div>
		</div>
	</nav>
</template>

<script>
	import ThemePicker from "./Navbar/ThemePicker";

	export default {
		name: "Navbar",
		components: {ThemePicker},

		created() {
			this.$router.options.routes.forEach((item) => {
				if (item.name !== '404') {
					this.routes.push(item);
				}
			});
		},

		data() {
			return {
				icons: {
					Accounting: 'file-invoice-dollar',
				},
				routes: []
			};
		}
	}
</script>

<style scoped lang="scss">
	.navbar-brand {
		font-family: mestizos-unidos;
	}

	.navbar-end {
		display: flex;
		justify-content: stretch;
	}
</style>
