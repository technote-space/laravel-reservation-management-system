import moment from 'moment';
import faker from '../faker';
import base from './base';

const format = date => date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
export default base('reservation', () => {
    const start = faker.date.between(moment().subtract(15, 'months').format('YYYY-MM-DD'), moment().add(6, 'months').format('YYYY-MM-DD'));
    const end = moment(start).add(faker.random.number({ max: 4 }), 'days').toDate();
    return {
        'guest_id': null,
        'room_id': null,
        'start_date': format(start),
        'end_date': format(end),
        status: 'reserved',
        checkout: '10:00:00',
    };
});
