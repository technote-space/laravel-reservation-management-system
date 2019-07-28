import { SET_DRAWER_ACTIVE, SET_DRAWER_STATE, SET_TOOLBAR_ACTIVE } from './constant';

const mutations = {
    [ SET_DRAWER_STATE ] (state, payload) {
        state.isOpenDrawer = payload.flag;
    },
    [ SET_DRAWER_ACTIVE ] (state, payload) {
        state.isActiveDrawer = payload.flag;
    },
    [ SET_TOOLBAR_ACTIVE ] (state, payload) {
        state.isActiveToolbar = payload.flag;
    },
};

export default mutations;
