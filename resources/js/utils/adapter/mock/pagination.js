import { take, drop } from 'lodash';
import store from '../../../store';
import models from './models';

export default (model, page) => {
    const perPage = 15;
    const items = drop(take(store.getters[ 'mock/getAllArray' ](model).map(item => models(model, item)), perPage * page), perPage * (page - 1));
    return {
        current_page: page,
        data: items,
        from: perPage * (page - 1) + 1,
        to: perPage * page,
        total: items.length,
        last_page: Math.ceil(items.length / perPage),
    };
}
