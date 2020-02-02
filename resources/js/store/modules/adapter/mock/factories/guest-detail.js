import faker from '../faker';
import base from './base';

export default base('guest-detail', () => ({
    'guest_id': null,
    name: faker.name.firstName() + ' ' + faker.name.lastName(),
    'name_kana': 'カナ',
    'zip_code': faker.address.zipCode(),
    address: faker.address.city(),
    phone: faker.phone.phoneNumber(),
}));
