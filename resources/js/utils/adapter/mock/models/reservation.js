const moment = require('moment');
import store from '../../../../store';
import guest from './guest';
import room from './room';
import detail from './reservation-detail';

export default (item, relation = true) => {
    const endDate = moment(item.end_date).add(1, 'days').format('YYYY-MM-DD');
    const checkinTime = store.getters[ 'adapter/getSetting' ]('checkin');
    const checkoutTime = store.getters[ 'adapter/getSetting' ]('checkout');
    const days = moment(item.end_date).diff(moment(item.start_date), 'days') + 1;
    const roomRaw = store.getters[ 'adapter/search' ]('rooms', room => room.id === item.room_id);
    const roomData = relation ? room(roomRaw) : roomRaw;
    return Object.assign({}, item, {
        detail: detail(store.getters[ 'adapter/search' ]('reservationDetails', detail => detail.reservation_id === item.id)),
        guest: guest(store.getters[ 'adapter/search' ]('guests', guest => guest.id === item.guest_id)),
        room: roomData,
        checkout: checkoutTime,
        'start_date_str': item.start_date,
        'start_datetime': item.start_date + `T${checkinTime}:00.000+0900`,
        'end_date_str': item.end_date,
        'end_datetime': endDate + `T${checkoutTime}:00.000+0900`,
        'is_past': moment(endDate + `T${checkoutTime}:00.000+0900`).isBefore(),
        'is_present': !moment(item.start_date + `T${checkinTime}:00.000+0900`).isAfter() && !moment(endDate + `T${checkoutTime}:00.000+0900`).isBefore(),
        'is_future': moment(item.start_date + `T${checkinTime}:00.000+0900`).isAfter(),
        days: days,
        charge: roomData.price * days,
    });
};
