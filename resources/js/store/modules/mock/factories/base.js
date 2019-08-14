import { pick } from 'lodash';
import { arrayToObject } from '../../../../utils/misc';

const moment = require('moment');
const ids = {};
const getId = model => {
    if (!(model in ids)) {
        ids[ model ] = 1;
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
