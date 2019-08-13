import faker from './faker';
import adminFactory from './factories/admin';
import guestFactory from './factories/guest';
import guestDetailFactory from './factories/guest-detail';
import roomFactory from './factories/room';
import reservationFactory from './factories/reservation';
import settingFactory from './factories/setting';
import env from './seeds/env';
import adminSeeder from './seeds/admin';
import roomSeeder from './seeds/room';
import settingSeeder from './seeds/setting';
import { arrayToObject } from '../../../utils/misc';

const state = {};
if ('local' === process.env.NODE_ENV || 'test' === process.env.NODE_ENV) {
    state.items = {};
    state.factories = {
        admins: adminFactory,
        guests: guestFactory,
        guestDetails: guestDetailFactory,
        rooms: roomFactory,
        reservations: reservationFactory,
        settings: settingFactory,
    };
    state.user = null;

    state.items.admins = adminSeeder(state.factories, env.number.admin);
    state.items.guests = guestFactory(env.number.guest).create();
    state.items.guestDetails = arrayToObject(Object.keys(state.items.guests), {
        getKey: ({ value }) => value.id,
        getItem: guest_id => guestDetailFactory().create({
            guest_id: guest_id - 0,
        }),
    });
    state.items.rooms = roomSeeder(state.factories, env.number.room);
    state.items.reservations = arrayToObject(Object.keys(state.items.guests), {
        getKey: ({ value }) => value.id,
        getItem: guest_id => [...Array(faker.random.number(env.number.reservation))].flatMap(() => reservationFactory().create({
            guest_id: guest_id - 0,
            room_id: Object.keys(state.items.rooms)[ faker.random.number({ min: 0, max: Object.keys(state.items.rooms).length - 1 }) ] - 0,
        })),
        isMultiple: true,
    });
    state.items.settings = settingSeeder(state.factories);
    console.info(state);
}

export default state;
