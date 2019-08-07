import Vue from 'vue';
import VeeValidate from 'vee-validate';
import { LANG_LIST } from '../configs/lang';
import i18n from './i18n';

Vue.use(VeeValidate, {
    i18n,
    i18nRootKey: 'validations',
    dictionary: Object.assign(...LANG_LIST.map(lang => ({
        [ lang ]: require('vee-validate/dist/locale/' + lang),
    }))),
});
