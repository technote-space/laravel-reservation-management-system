import faker from '../faker';
import base from './base';

export default base('admin', () => ({
    name: faker.name.firstName() + ' ' + faker.name.lastName(),
    email: faker.internet.email(),
    password: faker.random.alphaNumeric(8),
    remember_token: faker.random.alphaNumeric(10),
}));
