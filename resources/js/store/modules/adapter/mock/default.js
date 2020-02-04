import faker from './faker';
import adminFactory from './factories/admin';
import guestFactory from './factories/guest';
import guestDetailFactory from './factories/guest-detail';
import roomFactory from './factories/room';
import reservationFactory from './factories/reservation';
import reservationDetailFactory from './factories/reservation-detail';
import settingFactory from './factories/setting';
import { getEnv, getSetting } from '../../../../utils/env';
import adminSeeder from './seeds/admin';
import roomSeeder from './seeds/room';
import settingSeeder from './seeds/setting';
import { arrayToObject } from '../../../../utils/misc';

const state = {};
state.factories = {
    admins: adminFactory,
    guests: guestFactory,
    guestDetails: guestDetailFactory,
    rooms: roomFactory,
    reservations: reservationFactory,
    reservationDetails: reservationDetailFactory,
    settings: settingFactory,
};
state.user = null;

const items = localStorage.getItem('items');
const user = localStorage.getItem('user');
const version = localStorage.getItem('version');
if (items && user && version && version === getSetting('version')) {
    state.items = JSON.parse(items);
    state.user = JSON.parse(user);
} else {
    state.items = {};
    state.items.admins = adminSeeder(state.factories, getEnv('number.admin'));
    state.items.guests = guestFactory(getEnv('number.guest')).create();
    state.items.guestDetails = arrayToObject(Object.keys(state.items.guests), {
        getKey: ({ value }) => value.id,
        getItem: guestId => guestDetailFactory().create({
            'guest_id': guestId - 0,
        }),
    });
    state.items.rooms = roomSeeder(state.factories, getEnv('number.room'));
    state.items.reservations = arrayToObject(Object.keys(state.items.guests), {
        getKey: ({ value }) => value.id,
        getItem: guestId => [...Array(faker.random.number(getEnv('number.reservation')))].flatMap(() => reservationFactory().create({
            'guest_id': guestId - 0,
            'room_id': Object.keys(state.items.rooms)[ faker.random.number({ min: 0, max: Object.keys(state.items.rooms).length - 1 }) ] - 0,
        })),
        isMultiple: true,
    });
    state.items.reservationDetails = arrayToObject(Object.keys(state.items.reservations), {
        getKey: ({ value }) => value.id,
        getItem: () => reservationDetailFactory().create(),
    });
    state.items.settings = settingSeeder(state.factories);
}

console.info(state);

export default state;
