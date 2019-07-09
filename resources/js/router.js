import Router from 'vue-router';

const Accounting = () => import('./views/Accounting' /* webpackChunkName: "js/Accounting" */);
const PageNotFound = () => import('./views/PageNotFound' /* webpackChunkName: "js/PageNotFound" */);

const router = new Router({
	mode: 'history',
	routes: [
		{
			path: '/accounting',
			name: 'edit',
			component: Accounting,
		},

		{
			path: '/*',
			name: '404',
			component: PageNotFound,
		},
	]
});

export default router;
