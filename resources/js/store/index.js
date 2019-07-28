import Vue from 'vue';
import Vuex from 'vuex';
import * as actions from './actions';
import * as getters from './getters';
import common from './modules/common';
import loading from './modules/loading';

Vue.use(Vuex);

export default new Vuex.Store({
    actions,
    getters,
    modules: {
        common,
        loading,
    },
    strict: process.env.NODE_ENV !== 'production',
    plugins: [],
});
