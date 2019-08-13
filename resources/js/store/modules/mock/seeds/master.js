import { arrayToObject } from '../../../../utils/misc';

export default (model, factories, map, number) => {
    return Object.assign({}, arrayToObject(require('../../../../../seed/' + model + '.json'), {
        getItem: item => factories[ model ]().create(map(item)),
        getKey: ({ value }) => value.id,
    }), factories[ model ](number).create());
}
