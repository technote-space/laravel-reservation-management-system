import { cloneDeep } from 'lodash';
import * as actions from './actions';
import mutations from './mutations';
import * as getters from './getters';
import initialState from './default';

export default {
    namespaced: true,
    state: cloneDeep(initialState),
    getters,
    actions,
    mutations,
};
