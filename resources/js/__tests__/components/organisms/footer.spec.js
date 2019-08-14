import { mount } from '@vue/test-utils';
import Vuex from 'vuex';
import Footer from '../../../components/organisms/Footer';
import app from '../app';
import setupLocalVue from '../setupLocalVue';

describe('Footer', () => {
    it('should render footer', () => {
        const wrapper = mount(app(Footer, 'bottom'), setupLocalVue({
            store: new Vuex.Store({
                getters: {
                    getSns: () => [
                        { icon: 'mdi-github-circle', url: 'https://github.com' },
                        { icon: 'mdi-twitter-circle', url: 'https://twitter.com' },
                    ],
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot();

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('.v-icon')).toHaveLength(2);
        expect(wrapper.find('strong').text()).toBe('Reservation System');
        expect(wrapper.find('div.flex').text()).toContain(new Date().getFullYear());
    });
});
