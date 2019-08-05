import VueScrollTo from 'vue-scrollto';
import { SET_MODEL, SET_PAGE, SET_ID, SET_RESPONSE, ON_REQUIRED_REFRESH } from './constant';
import { apiAccess } from '../../../utils/api';
import { addErrorToasted } from '../../../utils/toasted';
import store from '../../../store';

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
    const model = store.getters[ 'crud/getTargetModel' ];
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
};

/**
 * @param context
 * @param model
 * @param data
 * @param check
 * @returns {Promise<*|undefined>}
 */
export const create = async (context, { model, data, check = true }) => {
    if (check && !store.getters[ 'crud/isCreatable' ](model)) {
        setModel(context, model);
        return await create(context, { model, data, check: false });
    }

    if (!store.getters[ 'crud/isCreatable' ](model)) {
        addErrorToasted('作成できません。');
        return;
    }

    await access(context, store.getters[ 'crud/getCreateMethod' ], 'create', store.getters[ 'crud/getCreateEntryPoint' ], async () => {
        await refreshList(context);
    }, data);
};

/**
 * @param context
 * @param model
 * @param id
 * @param data
 * @param check
 * @returns {Promise<*|undefined>}
 */
export const edit = async (context, { model, id, data, check = true }) => {
    if (check && !store.getters[ 'crud/isEditable' ](model, id)) {
        setModel(context, model);
        await setDetail(context, id);
        return await edit(context, { model, id, data, check: false });
    }

    if (!store.getters[ 'crud/isEditable' ](model, id)) {
        addErrorToasted('編集できません。');
        await fetchList(context);
        return;
    }

    await access(context, store.getters[ 'crud/getEditMethod' ], 'edit', store.getters[ 'crud/getEditEntryPoint' ], async () => {
        await refreshList(context);
    }, data);
};

/**
 * @param context
 * @param model
 * @param id
 * @param check
 * @returns {Promise<*|undefined>}
 */
export const destroy = async (context, { model, id, check = true }) => {
    if (check && !store.getters[ 'crud/isDeletable' ](model, id)) {
        setModel(context, model);
        await setDetail(context, id);
        return await destroy(context, { model, id, check: false });
    }

    if (!store.getters[ 'crud/isDeletable' ](model, id)) {
        addErrorToasted('削除できません。');
        await refreshList(context);
        return;
    }

    await access(context, store.getters[ 'crud/getDeleteMethod' ], 'delete', store.getters[ 'crud/getDeleteEntryPoint' ], async () => {
        await refreshList(context);
    });
};
