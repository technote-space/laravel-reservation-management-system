import Vue from 'vue';
import Meta from 'vue-meta';
import Router from 'vue-router';
import store from '../store';
import routes from './routes';

Vue.use(Router);
Vue.use(Meta);

const router = new Router({
    mode: 'history',
    base: ROUTE_BASE,
    routes,
});

router.beforeEach(async(to, from, next) => {
    await store.dispatch('common/closeDrawer');
    await store.dispatch('auth/checkAuth', { to, next });
});

export default router;
