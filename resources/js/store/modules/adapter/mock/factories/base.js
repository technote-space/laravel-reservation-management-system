import { pick, max, camelCase, map } from 'lodash';
import { arrayToObject } from '../../../../../utils/misc';
import store from '../../../../index';

const moment = require('moment');
const ids = {};
const getId = model => {
    model = camelCase(model) + 's';
    if (!(model in ids)) {
        ids[ model ] = store ? max(map(Object.keys(store.getters[ 'adapter/getAll' ](model)).concat(['0']), str => parseInt(str, 10))) + 1 : 1;
    }
    return ids[ model ]++;
};

const generate = (model, generator, overwrite) => {
    const modelId = 'id' in overwrite ? overwrite[ 'id' ] : getId(model);
    const defaultValue = generator(modelId);
    const keys = Object.keys(defaultValue).concat(['created_at']);
    return Object.assign({
        id: modelId,
        created_at: moment().format('YYYY-MM-DD hh:mm:ss'),
    }, defaultValue, pick(overwrite, keys), {
        updated_at: moment().format('YYYY-MM-DD hh:mm:ss'),
    });
};

export default (model, generator) => (count = undefined) => ({
    create: (overwrite = {}) => {
        if (undefined === count) {
            return generate(model, generator, overwrite);
        }
        if (0 >= count) {
            return {};
        }
        return arrayToObject([...Array(count)], {
            getItem: () => generate(model, generator, overwrite),
            getKey: ({ value }) => value.id,
        });
    },
});
