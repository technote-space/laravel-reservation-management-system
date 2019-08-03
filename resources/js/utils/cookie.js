import { get } from 'lodash';

export const getCookies = () => document.cookie.split(';').map(cookie => cookie.split('=')).map(([key, value]) => ({ [ key ]: value }));
export const getCookieValue = key => get(getCookies(), key, '');
export const getXsrfToken = () => getCookieValue('X-XSRF-TOKEN');
