import Vue from 'vue';

/**
 * @param {string} method method
 * @param {string} url url
 * @param {object} options options
 * @param {?object} options.data data
 * @param {?function} options.succeeded succeeded
 * @param {?function} options.failed failed
 * @param {?function} options.always always
 */
export const apiAccess = async (method, url, options = { data: undefined, succeeded: undefined, failed: undefined, always: undefined }) => {
    try {
        const response = await window.axios[ method.toLocaleString() ]('/api/' + url, options.data);
        if ('function' === typeof options.succeeded) {
            options.succeeded(response);
        }
    } catch (error) {
        if ('function' === typeof options.failed) {
            options.failed(error);
        } else {
            processError(error);
        }
    }
    if ('function' === typeof options.always) {
        options.always();
    }
};

/**
 * @param {string} url url
 * @param {object} options options
 * @param {?object} options.data data
 * @param {?function} options.succeeded succeeded
 * @param {?function} options.failed failed
 * @param {?function} options.always always
 */
export const apiGet = async (url, options = { data: undefined, succeeded: undefined, failed: undefined, always: undefined }) => await apiAccess('get', url, options);

/**
 * @param {string} url url
 * @param {object} options options
 * @param {?object} options.data data
 * @param {?function} options.succeeded succeeded
 * @param {?function} options.failed failed
 * @param {?function} options.always always
 */
export const apiPost = async (url, options = { data: undefined, succeeded: undefined, failed: undefined, always: undefined }) => await apiAccess('post', url, options);

/**
 * @param error
 */
export const processError = error => {
    if (error.response && error.response.data && error.response.data.errors) {
        Object.keys(error.response.data.errors).map(key => error.response.data.errors[ key ].map(message => key + ': ' + message)).flat().flat().forEach(message => {
            Vue.toasted.show(message, {
                type: 'error',
            });
        });
    } else {
        Vue.toasted.show(error.message, {
            type: 'error',
        });
    }
};
