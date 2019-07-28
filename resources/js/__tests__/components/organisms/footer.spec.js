import { shallowMount, createLocalVue } from '@vue/test-utils';
import Vuex from 'vuex';
import Vuetify from 'vuetify';
import Footer from '../../../components/organisms/Footer';

const localVue = createLocalVue();
localVue.use(Vuex);
localVue.use(Vuetify);

describe('Footer', () => {
    let getters;
    let store;

    beforeEach(() => {
        getters = {
            getTitle: () => 'テスト',
            'common/getIcons': () => [
                { icon: 'mdi-github-circle', url: 'https://github.com' },
                { icon: 'mdi-twitter-circle', url: 'https://twitter.com' },
            ],
        };
        store = new Vuex.Store({
            getters,
        });
    });

    it('should', () => {
        const wrapper = shallowMount(Footer, { store, localVue });
        expect(wrapper.element).toMatchSnapshot();
        expect(wrapper.isVueInstance()).toBeTruthy();
    });
});
