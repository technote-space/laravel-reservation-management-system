import Vue from 'vue';
import { ValidationProvider, ValidationObserver, localize } from 'vee-validate';
import { LANG_LIST } from '../../configs/lang';
import { arrayToObject } from '../../utils/misc';
import locale from '../../lang/detector';

require('./extend');

localize(arrayToObject(LANG_LIST, { getItem: key => require('vee-validate/dist/locale/' + key) }));
localize(locale);

Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
