import store from '../store/index'

class AuthMiddleware {
    auth(to, from, next) {
        if (store.getters['auth/loggedIn']) {
            return next();
        }
        return next('/login');
    }

    guest(to, from, next) {
        if (store.getters['auth/loggedIn']) {
            return next('/');
        }
        return next();
    }
}

export default new AuthMiddleware();
