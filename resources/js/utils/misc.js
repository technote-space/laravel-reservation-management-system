/**
 * @param {array} items items
 * @param {?function} getKey get key function
 * @param {?function} getItem get item function
 * @param {boolean} isMultiple is multiple?
 * @returns {object} object
 */
export const arrayToObject = (items, { getKey = undefined, getItem = undefined, isMultiple = false }) => !items.length ?
    {} :
    (
        isMultiple ? arrayToObjectMultiple(items, { getKey, getItem }) : arrayToObjectSingle(items, { getKey, getItem })
    );

/**
 * @param {array} items items
 * @param {?function} getKey get key function
 * @param {?function} getItem get item function
 * @param {boolean} isMultiple is multiple?
 * @returns {object} object
 */
const arrayToObjectSingle = (items, { getKey = undefined, getItem = undefined }) => Object.assign(...items.map(item => {
    const value = 'function' === typeof getItem ? getItem(item) : item;
    const key = 'function' === typeof getKey ? getKey({ value, item }) : item;
    return {
        [ key ]: value,
    };
}));

/**
 * @param {array} items items
 * @param {function} getKey get key function
 * @param {function} getItem get item function
 * @param {boolean} isMultiple is multiple?
 * @returns {object} object
 */
const arrayToObjectMultiple = (items, { getKey, getItem }) => Object.assign(...items.flatMap(item => {
    return getItem(item).map(value => ({ [ getKey({ value, item }) ]: value }));
}));
