import { mount } from '@vue/test-utils';
import Vuex from 'vuex';
import Drawer from '../../../components/organisms/Drawer';
import app from '../app';
import setupLocalVue from '../setupLocalVue';

describe('Drawer', () => {
    it('should not render drawer if not authenticated', () => {
        const wrapper = mount(app(Drawer, 'inner'), setupLocalVue({
            store: new Vuex.Store({
                getters: {
                    getMenu: () => [
                        { title: 'test1', icon: 'mdi-view-dashboard', to: '/test1' },
                        { title: 'test2', icon: 'mdi-settings', to: '/test2' },
                    ],
                    'common/isOpenDrawer': () => false,
                    'auth/isAuthenticated': () => false,
                    'auth/getUserName': () => 'テスト',
                },
                actions: {
                    'common/setDrawerOpen': () => {},
                    'auth/logout': () => {},
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot('not authenticated');

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('.v-navigation-drawer')).toHaveLength(0);
    });

    it('should not render drawer if not opened', () => {
        const wrapper = mount(app(Drawer, 'inner'), setupLocalVue({
            store: new Vuex.Store({
                getters: {
                    getMenu: () => [
                        { title: 'test1', icon: 'mdi-view-dashboard', to: '/test1' },
                        { title: 'test2', icon: 'mdi-settings', to: '/test2' },
                    ],
                    'common/isOpenDrawer': () => false,
                    'auth/isAuthenticated': () => true,
                    'auth/getUserName': () => 'テスト',
                },
                actions: {
                    'common/setDrawerOpen': () => {},
                    'auth/logout': () => {},
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot('not opened');

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('.v-navigation-drawer')).toHaveLength(1);
        expect(wrapper.findAll('.v-navigation-drawer.v-navigation-drawer--fixed')).toHaveLength(1);
        expect(wrapper.findAll('.v-navigation-drawer.v-navigation-drawer--close')).toHaveLength(1);
    });

    it('should render drawer', () => {
        const setDrawerOpen = jest.fn();
        const logout = jest.fn();
        const wrapper = mount(app(Drawer, 'inner'), setupLocalVue({
            store: new Vuex.Store({
                getters: {
                    getMenu: () => [
                        { title: 'test1', icon: 'mdi-view-dashboard', to: '/test1' },
                        { title: 'test2', icon: 'mdi-settings', to: '/test2' },
                    ],
                    'common/isOpenDrawer': () => true,
                    'auth/isAuthenticated': () => true,
                    'auth/getUserName': () => 'テスト',
                },
                actions: {
                    'common/setDrawerOpen': setDrawerOpen,
                    'auth/logout': logout,
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot('opened');

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('.v-navigation-drawer')).toHaveLength(1);
        expect(wrapper.findAll('.v-navigation-drawer.v-navigation-drawer--fixed')).toHaveLength(1);
        expect(wrapper.findAll('.v-navigation-drawer.v-navigation-drawer--close')).toHaveLength(0);
        expect(wrapper.findAll('.v-list-item__title')).toHaveLength(4);
        expect(wrapper.findAll('.v-list-item__title').at(0).text()).toBe('テスト');
        expect(wrapper.findAll('.v-list-item__title').at(1).text()).toBe('test1');
        expect(wrapper.findAll('.v-list-item__title').at(2).text()).toBe('test2');
        expect(wrapper.findAll('.v-list-item__title').at(3).text()).toBe('Logout');

        wrapper.find('#test-contents').trigger('click');
        expect(setDrawerOpen).toBeCalled();

        wrapper.findAll('.v-list-item__title').at(3).trigger('click');
        expect(logout).toBeCalled();
    });
});
