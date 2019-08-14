import { getListRouterSetting } from '../utils/crud';
import Dashboard from '../components/pages/Dashboard';
import Login from '../components/pages/Login';
import NotFound from '../components/pages/NotFound';

export default [
    {
        path: '/',
        name: 'top',
        component: Dashboard,
        meta: {},
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false,
        },
    },
    {
        path: '*',
        name: 'notFound',
        component: NotFound,
        meta: {},
    },
    getListRouterSetting('guests'),
    getListRouterSetting('rooms'),
    getListRouterSetting('reservations'),
];
