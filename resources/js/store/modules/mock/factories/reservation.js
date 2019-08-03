const faker = require('faker/locale/ja');
import base from './base';

const format = date => date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
export default base('reservation', () => ({
    start_date: format(faker.date.past()),
    end_date: format(faker.date.future()),
    number: faker.random.number({ min: 1, max: 2 }),
}));
