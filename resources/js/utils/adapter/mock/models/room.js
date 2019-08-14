const moment = require('moment');
import { orderBy, first, filter, take } from 'lodash';
import store from '../../../../store';

export default item => {
    const reservations = orderBy(store.getters[ 'mock/searchAll' ]('reservations', reservation => reservation.room_id === item.id), ['start_date'], 'desc');
    return Object.assign({}, item, {
        is_reserved: false,
        latest_reservation: first(reservations),
        latest_usage: first(filter(reservations, reservation => !moment(reservation.start_date).isAfter())),
        recent_usages: take(filter(reservations, reservation => !moment(reservation.start_date).isAfter()), 5),
    });
}
