import Router from 'vue-router';
import AuthMiddleware from "./middleware/AuthMiddleware";

const Accounting = () => import('./views/Accounting' /* webpackChunkName: "js/Accounting" */);
const Entities = () => import('./views/Entities' /* webpackChunkName: "js/Entities" */);
const Database = () => import('./views/Database' /* webpackChunkName: "js/Database" */);
const Login = () => import('./views/Auth/Login' /* webpackChunkName: "js/Login" */);
const Debts = () => import('./views/Debts' /* webpackChunkName: "js/Debts" */);
const PageNotFound = () => import('./views/PageNotFound' /* webpackChunkName: "js/PageNotFound" */);
const Notes = () => import( "./views/Notes"  /* webpackChunkName: "js/notes" */);


const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            redirect: '/accounting',
            meta: {
                hide: true
            }
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
            beforeEnter: AuthMiddleware.auth,
            meta: {
                icon: 'file-invoice-dollar',
            }
        }, {
            path: '/entities',
            name: 'Entities',
            component: Entities,
            beforeEnter: AuthMiddleware.auth,
            meta: {
                icon: 'id-card',
            }
        }, {
            path: '/debts',
            name: 'Debts',
            component: Debts,
            beforeEnter: AuthMiddleware.auth,
            meta: {
                icon: 'money-check-alt',
            }
        }, {
            path: '/database',
            name: 'Database',
            component: Database,
            beforeEnter: AuthMiddleware.auth,
            meta: {
                icon: 'database',
            }
        }, {
            path: '/notes/:entity',
            name: 'Notes',
            component: Notes,
            beforeEnter: AuthMiddleware.auth,
            meta: {
                hide: true
            }
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
