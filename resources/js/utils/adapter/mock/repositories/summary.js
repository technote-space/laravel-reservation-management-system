import moment from 'moment';
import store from '../../../../store';
import models from '../models';
import { arrayToObject } from '../../../misc';

const getPrice = item => item.detail.payment ? item.detail.payment - 0 : 0;
const getBy = type => {
    if ('daily' === type) {
        return 'days';
    }
    return 'months';
};
const getFormat = type => {
    if ('daily' === type) {
        return 'YYYY-MM-DD';
    }
    return 'YYYY-MM';
};

export default (data, model) => {
    if ('summary' === model) {
        const start = moment(data[ 'start_date' ]);
        const end = moment(data[ 'end_date' ]);
        const type = data[ 'type' ];

        const range = Array.from(moment.range(start, end.subtract(1, 'days')).by(getBy(type)));
        const summary = !range.length ? {} : arrayToObject(range, {
            getKey: ({ item }) => item.format(getFormat(type)),
            getItem: () => 0,
        });
        store.getters[ 'adapter/getAllArray' ]('reservations').filter(item => moment(item[ 'start_date' ]).isBetween(start, end, null, '[)')).map(item => models('reservations', item)).forEach(item => {
            summary[ moment(item[ 'start_date' ]).format(getFormat(type)) ] += getPrice(item);
        });

        return summary;
    }
};
