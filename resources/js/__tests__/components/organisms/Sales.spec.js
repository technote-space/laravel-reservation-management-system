import { mount } from '@vue/test-utils';
import Vuex from 'vuex';
import Sales from '../../../components/organisms/Sales';
import app from '../app';
import setupLocalVue from '../setupLocalVue';

jest.useFakeTimers();
jest.mock('../../../utils/api', () => ({
    apiGet: () => ({}),
}));
jest.mock('vue-chartjs', () => ({
    Bar: {
        render: () => undefined,
        methods: {
            renderChart: () => undefined,
        },
    },
}));

describe('Sales', () => {
    it('should render sales', () => {
        const wrapper = mount(app(Sales, 'inner', {
            props: {
                label: 'test',
                start: '2020-01-01',
                end: '2020-01-31',
                type: 'test',
                roomId: 1,
            },
        }), setupLocalVue({
            store: new Vuex.Store({
                state: {
                    data: [],
                    isLoading: false,
                },
                getters: {
                    'loading/isLoading': () => true,
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot();

        expect(wrapper.findAll('circle')).toHaveLength(1);
    });
});
