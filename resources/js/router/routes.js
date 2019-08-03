import { getListRouterSetting, getDetailRouterSetting } from '../store/modules/crud/utils';
import Dashboard from '../components/pages/Dashboard';
import Login from '../components/pages/Login';
import NotFound from '../components/pages/NotFound';

import GuestList from '../components/pages/list/Guest';
import GuestDetail from '../components/pages/detail/Guest';
import RoomList from '../components/pages/list/Room';
import RoomDetail from '../components/pages/detail/Room';
import ReservationList from '../components/pages/list/Reservation';
import ReservationDetail from '../components/pages/detail/Reservation';

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
    getListRouterSetting('guests', GuestList),
    getListRouterSetting('rooms', RoomList),
    getListRouterSetting('reservations', ReservationList),
    getDetailRouterSetting('guests', GuestDetail),
    getDetailRouterSetting('rooms', RoomDetail),
    getDetailRouterSetting('reservations', ReservationDetail),
];
