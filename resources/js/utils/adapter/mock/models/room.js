import { orderBy, sumBy } from 'lodash';
import store from '../../../../store';
import reservation from './reservation';

export default item => {
    const reservations = orderBy(store.getters[ 'adapter/searchAll' ]('reservations', reservation => reservation.room_id === item.id), ['start_date'], 'desc');
    return Object.assign({}, item, {
        total_sales: sumBy(reservations, function (item) {
            return reservation(item, false).detail.payment - 0;
        }),
    });
};
