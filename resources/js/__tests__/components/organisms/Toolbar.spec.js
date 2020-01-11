import { mount } from '@vue/test-utils';
import Vuex from 'vuex';
import Toolbar from '../../../components/organisms/Toolbar';
import app from '../app';
import setupLocalVue from '../setupLocalVue';

describe('Toolbar', () => {
    it('should not render toolbar if not active', () => {
        const wrapper = mount(app(Toolbar, 'top'), setupLocalVue({
            store: new Vuex.Store({
                getters: {
                    'common/isActiveToolbar': () => false,
                    'common/isActiveDrawer': () => false,
                    'auth/isAuthenticated': () => false,
                },
                actions: {
                    'common/openDrawer': () => {
                    },
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot('not active');

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('.v-toolbar__content')).toHaveLength(0);
    });

    it('should not render toolbar toggle if not active drawer', () => {
        const wrapper = mount(app(Toolbar, 'top'), setupLocalVue({
            store: new Vuex.Store({
                getters: {
                    'common/isActiveToolbar': () => true,
                    'common/isActiveDrawer': () => false,
                    'auth/isAuthenticated': () => true,
                },
                actions: {
                    'common/openDrawer': () => {
                    },
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot('drawer not active');

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('.v-toolbar__content')).toHaveLength(1);
        expect(wrapper.find('.title').text()).toBe('Reservation System');
        expect(wrapper.findAll('.v-app-bar__nav-icon')).toHaveLength(0);
    });

    it('should not render toolbar toggle if not authenticated', () => {
        const wrapper = mount(app(Toolbar, 'top'), setupLocalVue({
            store: new Vuex.Store({
                getters: {
                    'common/isActiveToolbar': () => true,
                    'common/isActiveDrawer': () => true,
                    'auth/isAuthenticated': () => false,
                },
                actions: {
                    'common/openDrawer': () => {
                    },
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot('not authenticated');

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('.v-toolbar__content')).toHaveLength(1);
        expect(wrapper.find('.title').text()).toBe('Reservation System');
        expect(wrapper.findAll('.v-app-bar__nav-icon')).toHaveLength(0);
    });

    it('should render toolbar', () => {
        const openDrawer = jest.fn();
        const wrapper = mount(app(Toolbar, 'top'), setupLocalVue({
            store: new Vuex.Store({
                getters: {
                    'common/isActiveToolbar': () => true,
                    'common/isActiveDrawer': () => true,
                    'auth/isAuthenticated': () => true,
                },
                actions: {
                    'common/openDrawer': openDrawer,
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot('active');

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('.v-toolbar__content')).toHaveLength(1);
        expect(wrapper.find('.title').text()).toBe('Reservation System');
        expect(wrapper.findAll('.v-app-bar__nav-icon')).toHaveLength(1);

        wrapper.find('.v-app-bar__nav-icon').trigger('click');
        expect(openDrawer).toBeCalled();
    });
});
