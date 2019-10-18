<template>
    <nav class="navbar has-shadow is-spaced">
        <div class="container">
            <div class="navbar-brand justify-content-between-touch">
                <RouterLink to="/" class="navbar-item">
                    <div class="is-size-3-desktop is-size-4-touch logo">
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
                    <a class="navbar-menu__button button" @click="logout">
                        <FontAwesomeIcon icon="sign-out-alt"/>
                        Logout
                    </a>
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
				},
				routes: []
			};
		},

		methods: {
			async logout(){
				await axios.get('/logout');
				this.$store.commit('auth/logout');
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
