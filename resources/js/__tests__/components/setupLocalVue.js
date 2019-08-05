import { createLocalVue } from '@vue/test-utils';
import Vuex from 'vuex';
import Vuetify from 'vuetify';
import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';

const localVue = createLocalVue();
localVue.use(Vuex);
localVue.use(Vuetify);
localVue.use(VueRouter);
localVue.use(VeeValidate);

export default (overwrite) => Object.assign({}, {
    localVue,
    vuetify: new Vuetify(),
    router: new VueRouter(),
}, overwrite);
