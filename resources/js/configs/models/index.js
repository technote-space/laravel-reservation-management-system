import { arrayToObject } from '../../utils/misc';

export default arrayToObject([
    require('./room').default,
    require('./guest').default,
    require('./reservation').default,
], { getKey: ({ item }) => item.slug });
