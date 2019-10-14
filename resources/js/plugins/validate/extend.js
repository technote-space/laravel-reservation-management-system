import { extend } from 'vee-validate';
import { email, max, min, numeric, regex, required } from 'vee-validate/dist/rules.umd';
import moment from 'moment';

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
