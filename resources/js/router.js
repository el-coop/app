import Router from 'vue-router';
import AuthMiddleware from "./middleware/AuthMiddleware";

const Accounting = () => import('./views/Accounting' /* webpackChunkName: "js/Accounting" */);
const Login = () => import('./views/Auth/Login' /* webpackChunkName: "js/Accounting" */);
const PageNotFound = () => import('./views/PageNotFound' /* webpackChunkName: "js/PageNotFound" */);

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            redirect: '/accounting',
        },
        {
            path: '/login',
            name: 'Login',
            component: Login,
            beforeEnter: AuthMiddleware.guest,
            meta: {
                hide: true
            }
        },
        {
            path: '/accounting',
            name: 'Accounting',
            component: Accounting,
            beforeEnter: AuthMiddleware.auth
        },
        {
            path: '/*',
            name: '404',
            component: PageNotFound,
            meta: {
                hide: true
            }
        },
    ]
});

router.beforeEach((to, from, next) => {
    router.app.loader = true;
    next();
});

router.afterEach((to, from) => {
    router.app.loader = false;
});

export default router;
