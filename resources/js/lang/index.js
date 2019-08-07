import { FALLBACK_LANG, LANG_LIST } from '../configs/lang';
import locale from './detector';
import { arrayToObject } from '../utils/misc';

const options = {
    locale,
    messages: arrayToObject(LANG_LIST, { getItem: key => require('./messages/' + key).default }),
    dateTimeFormats: arrayToObject(LANG_LIST, { getItem: key => require('./date/' + key).default }),
};

if (FALLBACK_LANG !== locale) {
    options.fallbackLocale = FALLBACK_LANG;
}

export default options;
