require('./bootstrap');
import 'babel-polyfill';
import Vue from 'vue';
import router from './router';
import store from './store';
import App from './components/App';
import vuetify from './plugins/vuetify';

new Vue({
    el: '#app',
    store,
    router,
    components: { App },
    template: '<App/>',
    vuetify,
});
