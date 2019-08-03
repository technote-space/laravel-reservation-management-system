const faker = require('faker/locale/ja');
import adminFactory from './factories/admin';
import guestFactory from './factories/guest';
import guestDetailFactory from './factories/guest-detail';
import roomFactory from './factories/room';
import reservationFactory from './factories/reservation';

const state = {};
if ('local' === process.env.NODE_ENV) {
    state.items = {};
    state.factories = {
        admins: adminFactory,
        guests: guestFactory,
        guestDetails: guestDetailFactory,
        rooms: roomFactory,
        reservations: reservationFactory,
    };
    state.user = null;

    state.items.admins = adminFactory(1).create({
        email: 'test@example.com',
        password: 'test1234',
    });

    state.items.guests = guestFactory(25).create();
    state.items.guestDetails = Object.assign(...Object.keys(state.items.guests).map(guest_id => guestDetailFactory().create({
        guest_id: guest_id - 0,
    })).map(item => ({ [ item.id ]: item })));
    state.items.rooms = roomFactory(5).create();
    state.items.reservations = Object.assign(...Object.keys(state.items.guests).flatMap(guest_id => [...Array(faker.random.number({ min: 1, max: 10 }))].flatMap(() => reservationFactory().create({
        guest_id: guest_id - 0,
        room_id: Object.keys(state.items.rooms)[ faker.random.number({ min: 0, max: Object.keys(state.items.rooms).length - 1 }) ] - 0,
    }))).map(item => ({ [ item.id ]: item })));
    console.info(state);
}

export default state;
