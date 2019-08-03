const moment = require('moment');
const ids = {};
const getId = model => {
    if (!(model in ids)) {
        ids[ model ] = 1;
    }
    return ids[ model ]++;
};

const generate = (model, generator, overwrite) => (Object.assign({
    id: getId(model),
    created_at: moment().format('YYYY-MM-DD hh:mm:ss'),
    updated_at: moment().format('YYYY-MM-DD hh:mm:ss'),
}, generator(), overwrite));

export default (model, generator) => (count = undefined) => ({
    create: (overwrite = {}) => {
        if (undefined === count) {
            return generate(model, generator, overwrite);
        }
        return Object.assign(...[...Array(count)].map(() => generate(model, generator, overwrite)).map(item => ({ [ item.id ]: item })));
    },
});
