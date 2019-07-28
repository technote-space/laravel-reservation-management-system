import Vue from 'vue';
import VueRouter from 'vue-router';
import Dashboard from '../components/pages/Dashboard';
import Login from '../components/pages/Login';
import NotFound from '../components/pages/NotFound';

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'top',
        component: Dashboard,
        meta: {
            title: 'Dashboard',
        },
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            title: 'Login',
        },
    },
    {
        path: '*',
        name: 'notFound',
        component: NotFound,
        meta: {
            title: 'Not Found',
        },
    },
];
const router = new VueRouter({
    mode: 'history',
    routes: routes,
});

router.afterEach((to) => {
    if (to.meta && to.meta.title) {
        document.title = to.meta.title;
    }
});

export default router;
