import Vue from 'vue';
import { ValidationProvider, ValidationObserver } from 'vee-validate';

require('./extend');

Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
