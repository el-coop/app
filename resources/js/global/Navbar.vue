<template>
    <nav class="navbar">
        <div class="navbar__title" v-text="$route.name"></div>
        <div class="navbar__menu">
            <RouterLink to="/" class="navbar__menu-item navbar__menu-item--brand logo">
                El-Coop
            </RouterLink>
            <ThemePicker class="navbar__menu-item"/>
            <RouterLink v-for="route in routes" :to="route.path" class="navbar__menu-item"
                        :key="route.name">
                <FontAwesomeIcon class="navbar__menu-item-icon" v-if="icons[route.name]" :icon="icons[route.name]" fixed-width/>
                <span class="navbar__menu-item-text" v-text="route.name"/>
            </RouterLink>
            <a class="navbar__menu-item navbar__menu-item--danger" @click="logout">
                <FontAwesomeIcon class="navbar__menu-item-icon" icon="sign-out-alt" fixed-width/>
                <span class="navbar__menu-item-text">Logout</span>
            </a>
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
				if (item.meta && item.meta.hide) {
					return;
				}
				this.routes.push(item);
			});
		},

		data() {
			return {
				icons: {
					Accounting: 'file-invoice-dollar',
					Entities: 'id-card',
				},
				routes: []
			};
		},

		methods: {
			logout() {
				this.$store.dispatch('auth/logout');
			}
		}
	}
</script>

<style scoped lang="scss">
    .navbar-end {
        display: flex;
        justify-content: stretch;
    }
</style>
