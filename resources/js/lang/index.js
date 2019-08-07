import { FALLBACK_LANG, LANG_LIST } from '../configs/lang';
import locale from './detector';

const options = {
    locale,
    messages: Object.assign(...LANG_LIST.map(lang => ({
        [ lang ]: require('./messages/' + lang).default,
    }))),
    dateTimeFormats: Object.assign(...LANG_LIST.map(lang => ({
        [ lang ]: require('./date/' + lang).default,
    }))),
};

if (FALLBACK_LANG !== locale) {
    options.fallbackLocale = FALLBACK_LANG;
}

export default options;
