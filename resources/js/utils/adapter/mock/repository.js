import { get } from 'lodash';
import store from '../../../store';
import models from './models';
import pagination from './pagination';
import summary from './summary';
import { arrayToObject } from '../../misc';

export default async(method, url, data = undefined) => {
    const split = url.split('?');
    const matches = split[ 0 ].match(/^(.+?)(\/(\d+))?$/);
    if (matches) {
        const model = matches[ 1 ];
        if ('user' === model) {
            return store.getters[ 'adapter/getUser' ];
        } else if ('login' === model) {
            const user = store.getters[ 'adapter/search' ]('admins', item => item.email === data.email && item.password === data.password);
            if (user) {
                await store.dispatch('adapter/login', user, { root: true });
            } else {
                throw Error('Failed to login');
            }
            return user;
        } else if ('logout' === model) {
            await store.dispatch('adapter/logout', undefined, { root: true });
            return false;
        } else if ('summary' === model) {
            return summary(data);
        } else if (matches[ 3 ]) {
            const id = matches[ 3 ] - 0;
            if ('get' === method) {
                return models(model, store.getters[ 'adapter/getItem' ](model, id));
            } else if ('patch' === method) {
                await store.dispatch('adapter/update', {
                    model,
                    id,
                    data,
                }, { root: true });
                return models(model, store.getters[ 'adapter/getItem' ](model, id));
            } else if ('delete' === method) {
                await store.dispatch('adapter/destroy', {
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
                await store.dispatch('adapter/create', {
                    model,
                    data,
                }, { root: true });
                return models(model, store.getters[ 'adapter/getLastItem' ](model));
            }
        }
    }
    throw Error('Not Found');
};
