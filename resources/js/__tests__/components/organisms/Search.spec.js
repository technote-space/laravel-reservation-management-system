import { mount } from '@vue/test-utils';
import Vuex from 'vuex';
import Search from '../../../components/organisms/Search';
import app from '../app';
import setupLocalVue from '../setupLocalVue';

jest.useFakeTimers();

describe('Search', () => {
    it('should render search', () => {
        const wrapper = mount(app(Search, 'inner'), setupLocalVue({
            store: new Vuex.Store({
                getters: {
                    'loading/isActiveOverlay': () => false,
                    'loading/getProgressColor': () => '',
                    'loading/getMessage': () => '',
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot();

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('circle')).toHaveLength(0);
    });
});
