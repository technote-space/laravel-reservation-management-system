import { MAIN_LANG, FALLBACK_LANG, LANG_LIST } from '../configs/lang';

/* istanbul ignore next */
const lang = (
    (window.navigator.languages && window.navigator.languages[ 0 ]) ||
    window.navigator.language ||
    window.navigator.userLanguage ||
    window.navigator.browserLanguage ||
    MAIN_LANG
).substr(0, 2).toLowerCase();

/* istanbul ignore next */
export default LANG_LIST.includes(lang) ? lang : FALLBACK_LANG;
