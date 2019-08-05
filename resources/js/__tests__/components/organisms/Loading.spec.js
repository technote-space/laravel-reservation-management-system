import { mount } from '@vue/test-utils';
import Vuex from 'vuex';
import Loading from '../../../components/organisms/Loading';
import app from '../app';
import setupLocalVue from '../setupLocalVue';

describe('Loading', () => {
    it('should not render loading if not active', () => {
        const wrapper = mount(app(Loading, 'bottom'), setupLocalVue({
            store: new Vuex.Store({
                getters: {
                    'loading/isActiveOverlay': () => false,
                    'loading/getMessage': () => '',
                    'loading/getProgressColor': () => undefined,
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot('not active');

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('.v-overlay__content')).toHaveLength(0);
    });

    it('should render loading', () => {
        const wrapper = mount(app(Loading, 'bottom'), setupLocalVue({
            store: new Vuex.Store({
                getters: {
                    'loading/isActiveOverlay': () => true,
                    'loading/getMessage': () => '',
                    'loading/getProgressColor': () => '#033',
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot('active');

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('.v-overlay__content')).toHaveLength(1);
        expect(wrapper.find('.v-overlay__content').text()).toBe('');
    });

    it('should render loading message', () => {
        const wrapper = mount(app(Loading, 'bottom'), setupLocalVue({
            store: new Vuex.Store({
                getters: {
                    'loading/isActiveOverlay': () => true,
                    'loading/getMessage': () => 'test message',
                    'loading/getProgressColor': () => undefined,
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot('message');

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('.v-overlay__content')).toHaveLength(1);
        expect(wrapper.find('.v-overlay__content').text()).toBe('test message');
    });
});
