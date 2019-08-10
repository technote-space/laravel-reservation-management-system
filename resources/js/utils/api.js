import store from '../store';
import router from '../router';
import adapter from './adapter';
import { addErrorToasted } from './toasted';

/**
 * @param {string} method method
 * @param {string} url url
 * @param {object} options options
 * @param {object?} options.data data
 * @param {function?} options.succeeded succeeded
 * @param {function?} options.failed failed
 * @param {function?} options.always always
 * @returns {Promise<{response, error}>}
 */
export const apiAccess = async (method, url, options = { data: undefined, succeeded: undefined, failed: undefined, always: undefined }) => {
    const failed = async error => {
        if ('function' === typeof options.failed) {
            await options.failed(error);
        } else {
            await processError(error);
        }
    };
    const succeeded = async response => {
        if ('function' === typeof options.succeeded) {
            await options.succeeded(response);
        }
    };

    const { response, error } = await adapter(method, url, options.data);
    if (error) {
        await failed(error);
    } else {
        if (!response.data) {
            await succeeded({ data: null });
        } else if ('object' !== typeof response.data) {
            await failed('type of response is not json.');
        } else {
            await succeeded(response);
        }
    }

    if ('function' === typeof options.always) {
        await options.always();
    }

    return { response, error };
};

/**
 * @param {string} url url
 * @param {object} options options
 * @param {object?} options.data data
 * @param {function?} options.succeeded succeeded
 * @param {function?} options.failed failed
 * @param {function?} options.always always
 * @returns {Promise<{response, error}>}
 */
export const apiGet = async (url, options = { data: undefined, succeeded: undefined, failed: undefined, always: undefined }) => await apiAccess('get', url, options);

/**
 * @param {string} url url
 * @param {object} options options
 * @param {object?} options.data data
 * @param {function?} options.succeeded succeeded
 * @param {function?} options.failed failed
 * @param {function?} options.always always
 * @returns {Promise<{response, error}>}
 */
export const apiPost = async (url, options = { data: undefined, succeeded: undefined, failed: undefined, always: undefined }) => await apiAccess('post', url, options);

/**
 * @param {string} url url
 * @param {object} options options
 * @param {object?} options.data data
 * @param {function?} options.succeeded succeeded
 * @param {function?} options.failed failed
 * @param {function?} options.always always
 * @returns {Promise<{response, error}>}
 */
export const apiPatch = async (url, options = { data: undefined, succeeded: undefined, failed: undefined, always: undefined }) => await apiAccess('patch', url, options);

/**
 * @param {string} url url
 * @param {object} options options
 * @param {object?} options.data data
 * @param {function?} options.succeeded succeeded
 * @param {function?} options.failed failed
 * @param {function?} options.always always
 * @returns {Promise<{response, error}>}
 */
export const apiDelete = async (url, options = { data: undefined, succeeded: undefined, failed: undefined, always: undefined }) => await apiAccess('delete', url, options);

/**
 * @returns {Promise<any>}
 */
export const refreshRoute = async () => await store.dispatch('auth/checkAuth', {
    to: router.currentRoute,
    next: location => location ? router.push(location) : null,
}, { root: true });

/**
 * @param error
 */
export const processError = async error => {
    if ('string' === typeof error) {
        addErrorToasted(error);
        //    } else if (error.response && 401 === error.response.status) {
        //        addInfoToasted('セッションがタイムアウトしました。再度ログインしてください。');
        //        await store.dispatch('auth/setUser', null);
        //        await refreshRoute();
        //    } else if (error.response && 419 === error.response.status) {
    } else if (error.response && (401 === error.response.status || 419 === error.response.status)) {
        const backTo = store.getters[ 'auth/getBackTo' ];
        if (backTo) {
            location.href = backTo;
        } else {
            location.reload();
        }
    } else if (error.response && error.response.data && error.response.data.errors) {
        Object.keys(error.response.data.errors).map(key => error.response.data.errors[ key ]).flat().flat().forEach(message => addErrorToasted(message));
    } else {
        addErrorToasted(error.message);
    }
};
