const faker = require('faker/locale/ja');
import base from './base';

export default base('room', (modelId) => ({
    name: '部屋' + modelId,
    number: faker.random.number({ min: 2, max: 10 }),
    price: faker.random.number({ min: 10000, max: 50000 }),
}));
