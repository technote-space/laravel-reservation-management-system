import faker from '../faker';
import base from './base';

export default base('room', () => ({
    name: faker.lorem.word(),
    number: faker.random.number({ min: 2, max: 10 }),
    price: faker.random.number({ min: 10000, max: 50000 }),
}));
