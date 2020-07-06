import { mount } from '@vue/test-utils';
import Vuex from 'vuex';
import Edit from '../../../components/organisms/Edit';
import app from '../app';
import setupLocalVue from '../setupLocalVue';

jest.useFakeTimers();

describe('Edit', () => {
    it('should render edit', () => {
        const wrapper = mount(app(Edit, 'inner', {
            props: {
                increment: 0,
                targetId: null,
                targetModel: 'test',
            },
        }), setupLocalVue({
            store: new Vuex.Store({
                state: {
                    formInputs: {},
                    nowModel: null,
                },
                actions: {
                    'crud/setModel': () => {
                    },
                },
                getters: {
                    'crud/getTargetModel': () => true,
                    'getModelForms': () => () => [],
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot();

        expect(wrapper.findAll('form')).toHaveLength(1);
        expect(wrapper.findAll('button')).toHaveLength(2);
    });
});
