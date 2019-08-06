import Vue from 'vue';

/**
 * @param {string} message message
 * @param {object} options options
 * @returns {ToastObject}
 */
export const addToasted = (message, options = {}) => Vue.toasted.show(message, options);

/**
 * @param {string} message message
 * @param {object} options options
 * @returns {ToastObject}
 */
export const addErrorToasted = (message, options = {}) => addToasted(message, Object.assign({
    type: 'error',
}, options));

/**
 * @param {string} message message
 * @param {object} options options
 * @returns {ToastObject}
 */
export const addInfoToasted = (message, options = {}) => addToasted(message, Object.assign({
    type: 'info',
}, options));

/**
 * @param {string} message message
 * @param {object} options options
 * @returns {ToastObject}
 */
export const addSuccessToasted = (message, options = {}) => addToasted(message, Object.assign({
    type: 'success',
}, options));
