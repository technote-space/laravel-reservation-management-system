import Vue from 'vue';
import Meta from 'vue-meta';
import Router from 'vue-router';
import store from '../store';
import routes from './routes';

Vue.use(Router);
Vue.use(Meta);

const router = new Router({
    mode: 'history',
    routes,
});

router.beforeEach((to, from, next) => {
    store.dispatch('common/closeDrawer');
    store.dispatch('auth/checkAuth', { to, next });
});

export default router;
