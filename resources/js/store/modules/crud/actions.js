import VueScrollTo from 'vue-scrollto';
import { SET_MODEL, SET_PAGE, SET_ID, SET_RESPONSE, ON_REQUIRED_REFRESH, CLEAR_CACHE } from './constant';
import { apiAccess } from '../../../utils/api';
import { addErrorToasted, addSuccessToasted } from '../../../utils/toasted';
import store from '../../../store';
import i18n from '../../../plugins/i18n';

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
 * @returns {Promise<{response, error}>}
 */
const access = async (context, method, name, url, succeeded, data = undefined) => {
    startLoading(context, name);
    return await apiAccess(method, url, {
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
 * @returns {Promise<{response, error}>}
 */
const getAccess = async (context, name, url, succeeded) => {
    const model = store.getters[ 'crud/getTargetModel' ];
    return await access(context, 'get', name, url, response => {
        succeeded(response, model);
        scrollToTop();
    });
};

/**
 * @param context
 * @returns {Promise<void>}
 */
const fetchList = async context => {
    if (!store.getters[ 'crud/isRequiredFetchList' ]) {
        scrollToTop();
        return;
    }

    await getAccess(context, 'fetchList', store.getters[ 'crud/getListEntryPoint' ], async (response, model) => {
        setResponse(context, response, model);
        if (store.getters[ 'crud/getPage' ] > store.getters[ 'crud/getTotalPage' ]) {
            await setPage(context, store.getters[ 'crud/getTotalPage' ]);
        }
    });
};

/**
 * @returns {Promise<void>}
 */
const refreshList = async (context) => {
    context.commit(ON_REQUIRED_REFRESH);
    await fetchList(context);
};

/**
 * @param context
 * @param id
 * @returns {Promise<void>}
 */
const fetchDetail = async (context, id) => {
    if (!store.getters[ 'crud/isRequiredFetchDetail' ]) {
        scrollToTop();
        return;
    }

    await getAccess(context, 'fetchDetail', store.getters[ 'crud/getDetailEntryPoint' ], (response, model) => {
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
 * @param id
 * @returns {Promise<void>}
 */
export const setDetail = async (context, id) => {
    context.commit(SET_ID, id);
    await fetchDetail(context, id);
    if (!store.getters[ 'crud/hasDetailCache' ]) {
        await refreshList(context);
    }
};

/**
 * @param context
 * @param model
 * @param data
 * @param check
 * @returns {Promise<boolean>}
 */
export const create = async (context, { model, data, check = true }) => {
    if (check && !store.getters[ 'crud/isCreatable' ](model)) {
        setModel(context, model);
        return await create(context, { model, data, check: false });
    }

    if (!store.getters[ 'crud/isCreatable' ](model)) {
        addErrorToasted(i18n.t('messages.failed.create'));
        return false;
    }

    const { error } = await access(context, store.getters[ 'crud/getCreateMethod' ], 'create', store.getters[ 'crud/getCreateEntryPoint' ], async () => {
        addSuccessToasted(i18n.t('messages.succeeded.create'));
        await refreshList(context);
    }, data);

    return !error;
};

/**
 * @param context
 * @param model
 * @param id
 * @param data
 * @param check
 * @returns {Promise<boolean>}
 */
export const edit = async (context, { model, id, data, check = true }) => {
    if (check && !store.getters[ 'crud/isEditable' ](model, id)) {
        setModel(context, model);
        await setDetail(context, id);
        return await edit(context, { model, id, data, check: false });
    }

    if (!store.getters[ 'crud/isEditable' ](model, id)) {
        addErrorToasted(i18n.t('messages.failed.edit'));
        await fetchList(context);
        return false;
    }

    const { error } = await access(context, store.getters[ 'crud/getEditMethod' ], 'edit', store.getters[ 'crud/getEditEntryPoint' ], async () => {
        addSuccessToasted(i18n.t('messages.succeeded.edit'));
        context.commit(CLEAR_CACHE, id);
        await refreshList(context);
    }, data);

    return !error;
};

/**
 * @param context
 * @param model
 * @param id
 * @param check
 * @returns {Promise<boolean>}
 */
export const destroy = async (context, { model, id, check = true }) => {
    if (check && !store.getters[ 'crud/isDeletable' ](model, id)) {
        setModel(context, model);
        await setDetail(context, id);
        return await destroy(context, { model, id, check: false });
    }

    if (!store.getters[ 'crud/isDeletable' ](model, id)) {
        addErrorToasted(i18n.t('messages.failed.delete'));
        await refreshList(context);
        return false;
    }

    const { error } = await access(context, store.getters[ 'crud/getDeleteMethod' ], 'delete', store.getters[ 'crud/getDeleteEntryPoint' ], async () => {
        addSuccessToasted(i18n.t('messages.succeeded.delete'));
        context.commit(CLEAR_CACHE, id);
        await refreshList(context);
    });

    return !error;
};

/**
 * @param context
 * @param model
 * @param query
 * @returns {Promise<Array>}
 */
export const search = async (context, { model, query }) => {
    if ('object' !== typeof query) {
        query = {};
    }
    if (!('count' in query)) {
        query.count = 0;
    }
    const { response, error } = await access(context, 'get', 'search', store.getters[ 'crud/getSearchEntryPoint' ](model), undefined, query);
    if (error) {
        return [];
    }
    return response.data;
};

/**
 * @param context
 * @param reservationId
 * @param roomId
 * @param guestId
 * @param startDate
 * @param endDate
 * @returns {Promise<boolean>}
 */
export const checkReservation = async (context, { reservationId, roomId, guestId, startDate, endDate }) => {
    const { response, error } = await access(context, 'post', 'check', store.getters[ 'crud/getReservationCheckEntryPoint' ], undefined, {
        'reservation_id': reservationId,
        'room_id': roomId,
        'guest_id': guestId,
        'start_date': startDate,
        'end_date': endDate,
    });

    if (error) {
        return false;
    }

    if (!response.data.result) {
        addErrorToasted(response.data.message);
    }

    return response.data.result;
};
