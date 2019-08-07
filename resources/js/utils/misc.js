export const arrayToObject = (items, { getKey = undefined, getItem = undefined, isMultiple = false }) => isMultiple ? arrayToObjectMultiple(items, { getKey, getItem }) : arrayToObjectSingle(items, { getKey, getItem });

const arrayToObjectSingle = (items, { getKey = undefined, getItem = undefined }) => Object.assign(...items.map(item => {
    const value = 'function' === typeof getItem ? getItem(item) : item;
    const key = 'function' === typeof getKey ? getKey({value, item}) : item;
    return {
        [ key ]: value,
    };
}));

const arrayToObjectMultiple = (items, { getKey, getItem = undefined }) => Object.assign(...items.flatMap(item => {
    const values = 'function' === typeof getItem ? getItem(item) : item;
    return values.map(value => ({ [ getKey({value, item}) ]: value }));
}));
