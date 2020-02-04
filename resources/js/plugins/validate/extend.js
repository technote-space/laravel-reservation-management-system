import { extend, configure } from 'vee-validate';
import { email, max, min, numeric, regex, required } from 'vee-validate/dist/rules.umd';
import moment from 'moment';
import i18n from '../i18n';

configure({
    defaultMessage: (field, values) => {
        // override the field name.
        values._field_ = i18n.t(`column.${field}`);

        return i18n.t(`validation.${values._rule_}`, values);
    },
});

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
