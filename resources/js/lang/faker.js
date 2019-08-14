import { LANG_LIST } from '../configs/lang';
import { arrayToObject } from '../utils/misc';

export default arrayToObject(LANG_LIST, {
    getItem: key => require('./faker/' + key).default,
});
