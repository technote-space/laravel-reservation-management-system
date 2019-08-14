const moment = require('moment');
import { orderBy, first, filter, take } from 'lodash';
import store from '../../../../store';
import detail from './guest-detail';

export default item => {
    const reservations = orderBy(store.getters[ 'adapter/searchAll' ]('reservations', reservation => reservation.guest_id === item.id), ['start_date'], 'desc');
    return Object.assign({}, item, {
        detail: detail(store.getters[ 'adapter/search' ]('guestDetails', detail => detail.guest_id === item.id)),
        latest_reservation: first(reservations),
        latest_usage: first(filter(reservations, reservation => !moment(reservation.start_date).isAfter())),
        recent_usages: take(filter(reservations, reservation => !moment(reservation.start_date).isAfter()), 5),
    });
}
