import Vue from 'vue';
import VeeValidate from 'vee-validate';
import { LANG_LIST } from '../configs/lang';
import i18n from './i18n';
import { arrayToObject } from '../utils/misc';

Vue.use(VeeValidate, {
    i18n,
    i18nRootKey: 'validations',
    dictionary: arrayToObject(LANG_LIST, { getItem: key => require('vee-validate/dist/locale/' + key) }),
});
