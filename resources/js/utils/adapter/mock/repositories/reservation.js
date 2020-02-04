import moment from 'moment';
import store from '../../../../store';
import models from '../models';

const model = 'reservations';

const getCheckin = data => {
    const date = data[ 'date' ] ? moment(data[ 'date' ]) : moment();
    const checkinTime = store.getters[ 'adapter/getSetting' ]('checkin');

    return store.getters[ 'adapter/getAllArray' ](model).filter(
        item => moment(`${item[ 'start_date' ]} ${checkinTime}`).isBetween(date.clone().startOf('day'), date.clone().endOf('day')) && 'canceled' !== item.status,
    ).map(item => models(model, item));
};

const getCheckout = data => {
    const date = data[ 'date' ] ? moment(data[ 'date' ]) : moment();

    return store.getters[ 'adapter/getAllArray' ](model).filter(
        item => moment(`${item[ 'end_date' ]} ${item[ 'checkout' ]}`).isBetween(date.clone().subtract(1, 'days').startOf('day'), date.clone().subtract(1, 'days').endOf('day')) && 'canceled' !== item.status,
    ).map(item => models(model, item));
};

const checkin = async data => {
    await store.dispatch('adapter/update', {
        model,
        id: data.id,
        data: {
            [ model ]: {
                status: 'checkin',
            },
        },
    });
    return { result: true };
};

const checkout = async data => {
    await store.dispatch('adapter/update', {
        model,
        id: data.id,
        data: {
            [ model ]: {
                status: 'checkout',
            },
        },
    });
    await store.dispatch('adapter/update', {
        model: 'reservationDetails',
        id: store.getters[ 'adapter/search' ]('reservationDetails', item => Number(item.reservation_id) === Number(data.id)).id,
        data: {
            reservationDetails: {
                payment: models(model, store.getters[ 'adapter/getItem' ](model, data.id)).charge,
            },
        },
    });
    return { result: true };
};

const cancel = async data => {
    await store.dispatch('adapter/update', {
        model,
        id: data.id,
        data: {
            [ model ]: {
                status: 'canceled',
            },
        },
    });
    return { result: true };
};

export default async (data, model, method) => {
    if ('cancel' === model) {
        return cancel(data);
    }

    if ('checkin' === model && 'get' === method) {
        return getCheckin(data);
    }

    if ('checkout' === model && 'get' === method) {
        return getCheckout(data);
    }

    if ('checkin' === model && 'patch' === method) {
        return checkin(data);
    }

    if ('checkout' === model && 'patch' === method) {
        return checkout(data);
    }
};
