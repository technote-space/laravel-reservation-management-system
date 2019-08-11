import 'babel-polyfill';

require('./bootstrap');
require('./plugins/validate');
require('./plugins/toasted');
require('./plugins/moment');

import Vue from 'vue';
import router from './router';
import store from './store';
import vuetify from './plugins/vuetify';
import i18n from './plugins/i18n';
import App from './components/App';

Vue.config.devtools = 'production' !== process.env.NODE_ENV && 'local' !== process.env.NODE_ENV;
Vue.config.productionTip = false;

new Vue({
    el: '#app',
    store,
    router,
    components: { App },
    template: '<App/>',
    vuetify,
    i18n,
});
