import Vue from 'vue';
import { ValidationProvider, ValidationObserver, extend, localize } from 'vee-validate';
import { required, min, max, numeric, regex, email } from 'vee-validate/dist/rules';
import moment from 'moment';
import { LANG_LIST } from '../configs/lang';
import { arrayToObject } from '../utils/misc';

extend('required', required);
extend('min', min);
extend('max', max);
extend('numeric', numeric);
extend('regex', regex);
extend('email', email);
extend('date_format', {
    validate (value, { format }) {
        return moment(value, format).isValid();
    },
    params: [
        {
            name: 'format',
            cast (value) {
                return value;
            },
        },
    ],
});

localize(arrayToObject(LANG_LIST, { getItem: key => require('vee-validate/dist/locale/' + key) }));

Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
