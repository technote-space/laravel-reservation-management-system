import { FALLBACK_LANG, LANG_LIST } from '../configs/lang';
import locale from './detector';
import { arrayToObject } from '../utils/misc';

export default {
    locale,
    messages: arrayToObject(LANG_LIST, {
        getItem: key => require('./messages/' + key).default,
    }),
    dateTimeFormats: arrayToObject(LANG_LIST, { getItem: key => require('./date/' + key).default }),
    faker: arrayToObject(LANG_LIST, { getItem: key => require('./faker/' + key).default }),
    fullCalendar: arrayToObject(LANG_LIST, { getItem: key => require('./fullCalendar/' + key).default }),
    fallbackLocale: FALLBACK_LANG,
};
