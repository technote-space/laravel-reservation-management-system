import { get } from 'lodash';
import admin from './admin';
import guest from './guest';
import reservation from './reservation';
import room from './room';

export default (model, item) => {
    if (!item) {
        throw Error('Not Found');
    }
    return get({
        admins: admin,
        guests: guest,
        reservations: reservation,
        rooms: room,
    }, model)(item);
}
