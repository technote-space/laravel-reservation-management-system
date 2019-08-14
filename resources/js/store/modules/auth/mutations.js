import { SET_INIT, SET_USER, SET_BACK_TO } from './constant';

const mutations = {
    [ SET_INIT ] (state) {
        state.initialized = true;
    },
    [ SET_USER ] (state, payload) {
        state.user = payload;
    },
    [ SET_BACK_TO ] (state, payload) {
        state.backTo = payload;
    },
};

export default mutations;
