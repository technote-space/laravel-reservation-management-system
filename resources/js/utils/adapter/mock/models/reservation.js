const moment = require('moment');
import store from '../../../../store';
import guest from './guest';
import room from './room';

export default item => Object.assign({}, item, {
    guest: guest(store.getters[ 'mock/search' ]('guests', guest => guest.id === item.guest_id)),
    room: room(store.getters[ 'mock/search' ]('rooms', room => room.id === item.room_id)),
    start_date_str: item.start_date,
    start_datetime: item.start_date + ' 15:00:00',
    end_date_str: item.end_date,
    end_datetime: item.end_date + ' 10:00:00',
    is_past: moment(item.end_date + ' 10:00:00').isBefore(),
    is_present: !moment(item.start_date + ' 15:00:00').isAfter() && !moment(item.end_date + ' 10:00:00').isBefore(),
    is_future: moment(item.start_date + ' 15:00:00').isAfter(),
});
