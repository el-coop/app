import Router from 'vue-router';

const Accounting = () => import('./views/Accounting' /* webpackChunkName: "js/Accounting" */);
const PageNotFound = () => import('./views/PageNotFound' /* webpackChunkName: "js/PageNotFound" */);

const router = new Router({
	mode: 'history',
	routes: [
		{
			path: '/accounting',
			name: 'Accounting',
			component: Accounting,
		},
		{
			path: '/*',
			name: '404',
			component: PageNotFound,
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
