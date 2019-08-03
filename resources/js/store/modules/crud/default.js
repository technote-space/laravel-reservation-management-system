import { STATE_MODE_TARGET, STATE_MODE_CURRENT } from './constant';
import headers from './headers';

const state = {
    [ STATE_MODE_TARGET ]: {
        model: null,
        list: {
            page: null,
        },
        detail: {
            id: null,
        },
    },
    [ STATE_MODE_CURRENT ]: {
        model: null,
        list: {
            page: null,
            total: null,
            totalPage: null,
            perPage: null,
            data: [],
        },
        detail: {
            id: null,
            caches: {},
        },
    },
    headers,
};

export default state;
