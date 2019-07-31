import { getXsrfToken } from './utils/cookie';

window.axios = require('axios');
window.axios.defaults.headers.common[ 'X-Requested-With' ] = 'XMLHttpRequest';
window.axios.interceptors.request.use(config => {
    config.headers[ 'X-XSRF-TOKEN' ] = getXsrfToken();
    return config;
});

window.siteParams = {
    title: document.head.querySelector('title').innerText,
};
