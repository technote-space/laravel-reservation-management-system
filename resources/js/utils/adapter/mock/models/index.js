import { get } from 'lodash';
import admin from './admin';
import guest from './guest';
import reservation from './reservation';
import room from './room';
import setting from './setting';

export default (model, item) => {
    if (!item) {
        throw Error('Not Found');
    }
    return get({
        admins: admin,
        guests: guest,
        reservations: reservation,
        rooms: room,
        settings: setting,
    }, model)(item);
};
