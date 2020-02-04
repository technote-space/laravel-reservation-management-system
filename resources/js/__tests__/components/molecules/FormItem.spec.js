import { mount } from '@vue/test-utils';
import Vuex from 'vuex';
import FormItem from '../../../components/molecules/FormItem';
import app from '../app';
import setupLocalVue from '../setupLocalVue';

jest.useFakeTimers();

describe('FormItem', () => {
    it('should not render date form', () => {
        const wrapper = mount(app(FormItem, 'inner', {
            props: {
                detail: {},
                formInputs: {},
                form: { type: 'date', text: 'id' },
                value: 'test',
                validateErrors: [],
                increment: 0,
            },
        }), setupLocalVue({
            store: new Vuex.Store({}),
        }));
        expect(wrapper.element).toMatchSnapshot('date form');

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('input[type="text"]')).toHaveLength(1);
    });

    it('should not render search form', () => {
        const wrapper = mount(app(FormItem, 'inner', {
            props: {
                detail: {},
                formInputs: {},
                form: { type: 'search', search: '::', icon: 'icon', text: 'id' },
                value: 'test',
                validateErrors: [],
                increment: 0,
            },
        }), setupLocalVue({
            store: new Vuex.Store({}),
        }));
        expect(wrapper.element).toMatchSnapshot('search form');

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('input[type="text"]')).toHaveLength(1);
    });

    it('should not render text form 1', () => {
        const wrapper = mount(app(FormItem, 'inner', {
            props: {
                detail: {},
                formInputs: {},
                form: { text: 'id' },
                value: 'test',
                validateErrors: [],
                increment: 0,
            },
        }), setupLocalVue({
            store: new Vuex.Store({}),
        }));
        expect(wrapper.element).toMatchSnapshot('text form 1');

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('input[type="text"]')).toHaveLength(1);
    });

    it('should not render text form 2', () => {
        const wrapper = mount(app(FormItem, 'inner', {
            props: {
                detail: {},
                formInputs: {},
                form: { hint: 'column.id', text: 'id' },
                value: 123,
                validateErrors: [],
                increment: 0,
            },
        }), setupLocalVue({
            store: new Vuex.Store({}),
        }));
        expect(wrapper.element).toMatchSnapshot('text form 2');

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('input[type="text"]')).toHaveLength(1);
    });

    it('should not render time form', () => {
        const wrapper = mount(app(FormItem, 'inner', {
            props: {
                detail: {},
                formInputs: {
                    'reservations.checkout': '10:00:00',
                },
                form: { type: 'time', text: 'id' },
                value: 'test',
                validateErrors: [],
                increment: 0,
            },
        }), setupLocalVue({
            store: new Vuex.Store({}),
        }));
        expect(wrapper.element).toMatchSnapshot('time form');

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('input[type="text"]')).toHaveLength(1);
    });
});
