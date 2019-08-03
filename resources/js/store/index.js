import Vue from 'vue';
import Vuex from 'vuex';
import * as actions from './actions';
import * as getters from './getters';
import common from './modules/common';
import auth from './modules/auth';
import crud from './modules/crud';
import loading from './modules/loading';
import mock from './modules/mock';

Vue.use(Vuex);

const modules = {
    common,
    auth,
    crud,
    loading,
};
if ('local' === process.env.NODE_ENV) {
    modules.mock = mock;
}

export default new Vuex.Store({
    actions,
    getters,
    modules,
    strict: 'production' !== process.env.NODE_ENV && 'local' !== process.env.NODE_ENV,
    plugins: [],
});
