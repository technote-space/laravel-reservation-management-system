import { get } from 'lodash';
import store from '../../../store';
import models from './models';
import pagination from './pagination';
import { arrayToObject } from '../../misc';

export default async (method, url, data = undefined) => {
    const split = url.split('?');
    const matches = split[ 0 ].match(/^(.+?)(\/(\d+))?$/);
    if (matches) {
        const model = matches[ 1 ];
        if ('user' === model) {
            return store.getters[ 'mock/getUser' ];
        } else if ('login' === model) {
            const user = store.getters[ 'mock/search' ]('admins', item => item.email === data.email && item.password === data.password);
            await store.dispatch('mock/login', user, { root: true });
            return user;
        } else if ('logout' === model) {
            await store.dispatch('mock/logout', undefined, { root: true });
            return false;
        } else if (matches[ 3 ]) {
            const id = matches[ 3 ] - 0;
            if ('get' === method) {
                return models(model, store.getters[ 'mock/getItem' ](model, id));
            } else if ('patch' === method) {
                await store.dispatch('mock/update', {
                    model,
                    id,
                    data,
                }, { root: true });
                return models(model, store.getters[ 'mock/getItem' ](model, id));
            } else if ('delete' === method) {
                await store.dispatch('mock/destroy', {
                    model,
                    id,
                }, { root: true });
                return { result: 1 };
            }
        } else {
            if ('get' === method) {
                const params = arrayToObject((split.length > 1 ? split[ 1 ] : '').split('&'), {
                    getItem: item => item.split('=')[ 1 ],
                    getKey: ({ item }) => item.split('=')[ 0 ],
                });
                const page = data && 'page' in data ? data.page : get(params, 'page', 1);
                const count = data && 'count' in data ? data.count : get(params, 'count', null);
                return pagination(model, page - 0, count);
            } else if ('post' === method) {
                await store.dispatch('mock/create', {
                    model,
                    data,
                }, { root: true });
                return models(model, store.getters[ 'mock/getLastItem' ](model));
            }
        }
    }
    throw Error('Not Found');
};
