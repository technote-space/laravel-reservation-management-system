import Vue from 'vue';

/**
 * @param method
 * @param url
 * @param options
 * @param options.data
 * @param options.succeeded
 * @param options.failed
 * @param options.always
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
 * @param url
 * @param options
 * @param options.data
 * @param options.succeeded
 * @param options.failed
 * @param options.always
 */
export const apiGet = async (url, options = { data: undefined, succeeded: undefined, failed: undefined, always: undefined }) => await apiAccess('get', url, options);

/**
 * @param url
 * @param options
 * @param options.data
 * @param options.succeeded
 * @param options.failed
 * @param options.always
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
