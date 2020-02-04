import store from '../../../../store';
import models from '../models';
import pagination from './pagination';
import { arrayToObject } from '../../../misc';
import { get } from 'lodash';

const getItem = (model, id) => models(model, store.getters[ 'adapter/getItem' ](model, id));

const updateItem = async (model, id, data) => {
    await store.dispatch('adapter/update', { model, id, data }, { root: true });
    return models(model, store.getters[ 'adapter/getItem' ](model, id));
};

const deleteItem = async (model, id) => {
    await store.dispatch('adapter/destroy', { model, id }, { root: true });
    return { result: 1 };
};

const createItem = async (model, data) => {
    await store.dispatch('adapter/create', { model, data }, { root: true });
    return models(model, store.getters[ 'adapter/getLastItem' ](model));
};

export default async (data, model, method, id, query) => {
    if (id) {
        if ('get' === method) {
            return getItem(model, id);
        }
        if ('patch' === method) {
            return updateItem(model, id, data);
        }
        if ('delete' === method) {
            return deleteItem(model, id);
        }
    } else {
        if ('get' === method) {
            const params = arrayToObject(query, {
                getItem: item => item.split('=')[ 1 ],
                getKey: ({ item }) => item.split('=')[ 0 ],
            });
            const page = data && 'page' in data ? data.page : get(params, 'page', 1);
            const count = data && 'count' in data ? data.count : get(params, 'count', null);
            return pagination(model, page - 0, count);
        }
        if ('post' === method) {
            return createItem(model, data);
        }
    }

    throw Error('Not Found');
};
