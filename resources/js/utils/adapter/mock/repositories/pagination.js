import { take, drop } from 'lodash';
import store from '../../../../store';
import models from '../models';

export default (model, page, count) => {
    const all = store.getters[ 'adapter/getAllArray' ](model);
    if (null !== count) {
        if (0 < count) {
            return all.slice(0, count).map(item => models(model, item));
        }
        return all.map(item => models(model, item));
    }

    if (0 >= page) {
        page = 1;
    }
    const perPage = 10;
    const items = drop(take(all.map(item => models(model, item)), perPage * page), perPage * (page - 1));
    return {
        'current_page': page,
        data: items,
        from: perPage * (page - 1) + 1,
        to: perPage * page,
        total: all.length,
        'last_page': Math.ceil(all.length / perPage),
        'per_page': perPage,
    };
};
