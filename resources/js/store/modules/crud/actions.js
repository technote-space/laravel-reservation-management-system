import VueScrollTo from 'vue-scrollto';
import { SET_MODEL, SET_PAGE, SET_ID, SET_RESPONSE } from './constant';
import { apiAccess } from '../../../utils/api';
import { isRequiredFetchList, isRequiredFetchDetail, getListEntryPoint, getDetailEntryPoint, getTargetModel, isEditable } from './getters';

const startLoading = (context, name) => {
    context.dispatch('loading/onLoading', 'crud/' + name, { root: true });
};

const endLoading = (context, name) => {
    context.dispatch('loading/offLoading', 'crud/' + name, { root: true });
};

const setResponse = (context, response, model, id = undefined) => context.commit(SET_RESPONSE, { model, id, response });

const scrollToTop = () => VueScrollTo.scrollTo('body', 600);

/**
 * @param context
 * @param method
 * @param name
 * @param url
 * @param succeeded
 * @param data
 * @returns {Promise<void>}
 */
const access = async (context, method, name, url, succeeded, data = undefined) => {
    startLoading(context, name);
    await apiAccess(method, url, {
        succeeded,
        always: () => {
            endLoading(context, name);
        },
        data,
    });
};

/**
 * @param context
 * @param name
 * @param url
 * @param succeeded
 * @returns {Promise<void>}
 */
const getAccess = async (context, name, url, succeeded) => {
    const model = getTargetModel(context.state);
    await access(context, 'get', name, url, response => {
        succeeded(response, model);
        scrollToTop();
    });
};

/**
 * @param context
 * @returns {Promise<void>}
 */
const fetchList = async context => {
    if (!isRequiredFetchList(context.state)) {
        scrollToTop();
        return;
    }
    await getAccess(context, 'fetchList', getListEntryPoint(context.state), (response, model) => {
        setResponse(context, response, model);
    });
};

/**
 * @param context
 * @param id
 * @returns {Promise<void>}
 */
const fetchDetail = async (context, id) => {
    if (!isRequiredFetchDetail(context.state, id)) {
        scrollToTop();
        return;
    }

    await getAccess(context, 'fetchDetail', getDetailEntryPoint(context.state, id), (response, model) => {
        setResponse(context, response, model, id);
    });
};

/**
 * @param context
 * @param model
 */
export const setModel = (context, model) => {
    context.commit(SET_MODEL, model);
};

/**
 * @param context
 * @param page
 * @returns {Promise<void>}
 */
export const setPage = async (context, page) => {
    context.commit(SET_PAGE, page);
    await fetchList(context);
};

/**
 * @param context
 * @returns {Promise<void>}
 */
export const nextPage = context => setPage(context, context.state.page + 1);

/**
 * @param context
 * @returns {Promise<void>}
 */
export const prevPage = context => setPage(context, context.state.page - 1);

/**
 * @param context
 * @param id
 * @returns {Promise<void>}
 */
export const setDetail = async (context, id) => {
    context.commit(SET_ID, id);
    await fetchDetail(context, id);
};

export const create = async (context, model, data) => {

};

export const edit = async (context, model, id, key, value) => {
    if (!isEditable(context.state, model, id)) {
        setModel(context, model);
        return setDetail(context, id);
    }

};

export const destroy = async (context, model, id) => {

};
