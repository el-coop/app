import { createRouter, createWebHistory } from 'vue-router'
import AuthMiddleware from "./middleware/AuthMiddleware";

const Accounting = () => import('./views/Accounting' /* webpackChunkName: "Accounting" */);
const Entities = () => import('./views/Entities' /* webpackChunkName: "Entities" */);
const Database = () => import('./views/Database' /* webpackChunkName: "Database" */);
const Login = () => import('./views/Auth/Login' /* webpackChunkName: "Login" */);
const Debts = () => import('./views/Debts' /* webpackChunkName: "Debts" */);
const PageNotFound = () => import('./views/PageNotFound' /* webpackChunkName: "PageNotFound" */);


const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            redirect() {
                return localStorage.getItem('last-visited-route') || 'accounting';
            },
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
        },
        {
            path: '/:pathMatch(.*)*',
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
    if (to.fullPath !== '/login') {
        localStorage.setItem('last-visited-route', to.fullPath);
    }
});

export default router;
