import moment from 'moment';
import i18n from '../plugins/i18n';

export const date = value => i18n.d(moment(value).toDate(), 'long');
export const number = value => i18n.tc('unit.number', value, { value });
export const price = value => null === value || '' === value ? '-----' : i18n.t('unit.price', { value });
export const generator = callback => (value, path, data) => callback(data, value, path);
