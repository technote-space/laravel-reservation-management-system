import { createLocalVue } from '@vue/test-utils';
import Vuex from 'vuex';
import Vuetify from 'vuetify';
import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';
import VueI18n from 'vue-i18n';
import options from '../../lang';

const localVue = createLocalVue();
localVue.use(Vuex);
localVue.use(Vuetify);
localVue.use(VueRouter);
localVue.use(VeeValidate);
localVue.use(VueI18n);

export default (overwrite) => Object.assign({}, {
    localVue,
    vuetify: new Vuetify(),
    router: new VueRouter(),
    i18n: new VueI18n(Object.assign({}, options, {
        locale: 'en',
        messages: {
            en: Object.assign({}, options.messages.en, {
                test1: 'Test1',
                test2: 'Test2',
                loading: {
                    'test_message': 'Test Message',
                },
            }),
        },
    })),
}, overwrite);