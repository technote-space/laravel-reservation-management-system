import { mount } from '@vue/test-utils';
import Vuex from 'vuex';
import Reservation from '../../../components/organisms/Reservation';
import app from '../app';
import setupLocalVue from '../setupLocalVue';

jest.useFakeTimers();

describe('Reservation', () => {
    it('should render reservation', () => {
        const wrapper = mount(app(Reservation, 'inner', {
            props: {
                roomId: 0,
            },
        }), setupLocalVue({
            store: new Vuex.Store({
                state: {
                    dialog: false,
                    format: 'YYYY-MM-DD',
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot();

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('button')).toHaveLength(1);
    });
});
