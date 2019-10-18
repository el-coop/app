<template>
    <div class="centered-content">
        <div class="login-wrapper">
            <div class="has-text-centered">
                <h4 class="logo title">
                    El-Coop
                </h4>
                <h5 class="subtitle is-6">
                    app
                </h5>
            </div>
            <form @submit.prevent="submit">
                <TextField :options="{type: 'text', 'label': 'Email'}" v-model="email" :error="error"/>
                <TextField :options="{type: 'password', 'label': 'Password'}" v-model="password"/>
                <button class="button is-primary is-fullwidth" :class="{'is-loading': loading}">
                    Log In
                </button>
            </form>

        </div>
    </div>
</template>

<script>
	import TextField from "../../global/Fields/TextField";

	export default {
		name: "Login",
		components: {TextField},

		data() {
			return {
				email: '',
				password: '',
				loading: false,
				error: '',
			}
		},

		methods: {
			async submit() {
				this.error = '';
				this.loading = true;
				try {
					await this.$store.dispatch('auth/login', {
						email: this.email,
						password: this.password
					});
					this.$router.push('/');
				} catch (e) {
					this.error = 'These credentials do not match our records.';
				}
				this.loading = false;

			},
		}
	}
</script>

<style scoped>
    .centered-content {
        display: flex;
        height: 100%;
        width: 100%;
        justify-content: center;
        align-items: center;
    }

    .login-wrapper {
        width: 100%;
        max-width: 400px;
        padding: 3rem 1.5rem;
    }
</style>
