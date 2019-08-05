import { getXsrfToken } from './utils/cookie';

window.axios = require('axios');
window.axios.defaults.headers.common[ 'X-Requested-With' ] = 'XMLHttpRequest';
window.axios.interceptors.request.use(config => {
    config.headers[ 'X-XSRF-TOKEN' ] = getXsrfToken();
    return config;
});
window.axios.interceptors.response.use(response => {
    return Promise.resolve({
        response,
    });
}, error => {
    return Promise.resolve({
        error,
    });
});

window.siteParams = {
    title: document.head.querySelector('title').innerText,
};
