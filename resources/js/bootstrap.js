window._ = require('lodash');
window.Popper = require('popper.js').default;

window.axios = require('axios');
window.axios.defaults.headers.common[ 'X-Requested-With' ] = 'XMLHttpRequest';
window.axios.defaults.headers.common[ 'X-CSRF-TOKEN' ] = document.head.querySelector('meta[name="csrf-token"]').content;

window.siteParams = {
    title: document.head.querySelector('title').innerText,
};
