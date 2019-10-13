import { mount } from '@vue/test-utils';
import Vuex from 'vuex';
import LoginForm from '../../../components/organisms/LoginForm';
import app from '../app';
import setupLocalVue from '../setupLocalVue';
import flushPromises from 'flush-promises';

describe('LoginForm', () => {
    it('should render login form', async () => {
        const login = jest.fn(() => setTimeout(() => {}, 100));
        const wrapper = mount(app(LoginForm, 'inner'), setupLocalVue({
            store: new Vuex.Store({
                state: {
                    loginForm: {
                        email: '',
                        password: '',
                    },
                    passwordVisibility: false,
                },
                actions: {
                    'auth/login': login,
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot();

        expect(wrapper.isVueInstance()).toBeTruthy();
        expect(wrapper.findAll('input')).toHaveLength(2);
        expect(wrapper.findAll('input[type="text"]')).toHaveLength(1);
        expect(wrapper.findAll('input[type="password"]')).toHaveLength(1);
        expect(wrapper.findAll('button')).toHaveLength(1);
        expect(wrapper.find('button').text()).toBe('Login');
        expect(wrapper.findAll('.v-input__icon--append i')).toHaveLength(1);

        wrapper.find('.v-input__icon--append i').trigger('click');
        await flushPromises();
        expect(wrapper.findAll('input[type="text"]')).toHaveLength(2);
        wrapper.find('.v-input__icon--append i').trigger('click');

        const email = wrapper.find('input[type="text"]');
        const password = wrapper.find('input[type="password"]');

        email.setValue('123');
        password.setValue('12345');
        expect(email.element.value).toBe('123');
        expect(password.element.value).toBe('12345');
        await flushPromises();
        expect(wrapper.find('button').is('[disabled]')).toBe(true);
        wrapper.find('button').trigger('click');
        await flushPromises();
        expect(wrapper.element).toMatchSnapshot();
        expect(wrapper.find('.v-counter').text()).toBe('5');
        expect(login).not.toBeCalled();

        email.setValue('test@example.com');
        password.setValue('password');
        expect(email.element.value).toBe('test@example.com');
        expect(password.element.value).toBe('password');
        await flushPromises();
        expect(wrapper.find('button').is('[disabled]')).toBe(false);
        wrapper.find('button').trigger('click');
        wrapper.find('button').trigger('click');
        await flushPromises();
        expect(wrapper.element).toMatchSnapshot();
        expect(wrapper.find('.v-counter').text()).toBe('8');
        expect(login).toBeCalledTimes(1);
    });
});
