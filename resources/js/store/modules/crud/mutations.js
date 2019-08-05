import { STATE_MODE_TARGET, STATE_MODE_CURRENT, ON_REQUIRED_REFRESH } from './constant';
import { SET_MODEL, SET_PAGE, SET_ID, SET_RESPONSE } from './constant';

const mutations = {
    [ SET_MODEL ] (state, payload) {
        state[ STATE_MODE_TARGET ].model = payload;
    },
    [ SET_PAGE ] (state, payload) {
        state[ STATE_MODE_TARGET ].list.page = payload;
    },
    [ SET_ID ] (state, payload) {
        state[ STATE_MODE_TARGET ].detail.id = payload;
    },
    [ SET_RESPONSE ] (state, { model, id, response }) {
        const update = Object.assign({}, state[ STATE_MODE_CURRENT ]);
        if (update.model !== model) {
            update.detail.caches = {};
        }

        update.model = model;
        if (!id) {
            update.list.page = response.data.current_page;
            update.list.total = response.data.total;
            update.list.totalPage = response.data.last_page;
            update.list.perPage = response.data.per_page;
            update.list.data = response.data.data;
        } else {
            update.detail.id = id;
            update.detail.caches[ id ] = response.data;
        }
        state[ STATE_MODE_CURRENT ] = update;
        state.isRequiredRefresh = false;
    },
    [ ON_REQUIRED_REFRESH ] (state) {
        state.isRequiredRefresh = true;
    },
};

export default mutations;
