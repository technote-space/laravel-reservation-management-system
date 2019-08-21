import { orderBy, first, filter, take } from 'lodash';
import { orderBy, sumBy } from 'lodash';
import store from '../../../../store';
import reservation from './reservation';

export default item => {
    const reservations = orderBy(store.getters[ 'adapter/searchAll' ]('reservations', reservation => reservation.room_id === item.id), ['start_date'], 'desc');
    return Object.assign({}, item, {
        is_reserved: false,
        latest_reservation: first(reservations),
        latest_usage: first(filter(reservations, reservation => !moment(reservation.start_date).isAfter())),
        recent_usages: take(filter(reservations, reservation => !moment(reservation.start_date).isAfter()), 5),
        total_sales: sumBy(reservations, function (item) {
            return reservation(item, false).detail.payment - 0;
        }),
    });
}
