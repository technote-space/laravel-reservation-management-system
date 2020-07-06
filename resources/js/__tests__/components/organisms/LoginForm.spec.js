import { mount } from '@vue/test-utils';
import Vuex from 'vuex';
import LoginForm from '../../../components/organisms/LoginForm';
import app from '../app';
import setupLocalVue from '../setupLocalVue';
import { flush } from '../../utils';

jest.useFakeTimers();

describe('LoginForm', () => {
    it('should render login form', async () => {
        const login = jest.fn(() => setTimeout(() => {
        }, 100));
        const wrapper = mount(app(LoginForm, 'inner'), setupLocalVue({
            store: new Vuex.Store({
                state: {
                    loginForm: {
                        email: '',
                        password: '',
                    },
                    passwordVisibility: false,
                    submitting: false,
                },
                actions: {
                    'auth/login': login,
                },
            }),
        }));
        expect(wrapper.element).toMatchSnapshot();

        expect(wrapper.findAll('input')).toHaveLength(2);
        expect(wrapper.findAll('input[type="text"]')).toHaveLength(1);
        expect(wrapper.findAll('input[type="password"]')).toHaveLength(1);
        expect(wrapper.findAll('button')).toHaveLength(2);
        expect(wrapper.find('button[type="submit"]').text()).toBe('Login');
        expect(wrapper.findAll('.v-input__icon--append button')).toHaveLength(1);

        wrapper.find('.v-input__icon--append button').trigger('click');
        await flush();
        expect(wrapper.findAll('input[type="text"]')).toHaveLength(2);
        wrapper.find('.v-input__icon--append button').trigger('click');

        await flush();
        const email = wrapper.find('input[type="text"]');
        const password = wrapper.find('input[type="password"]');

        email.setValue('123');
        password.setValue('12345');
        expect(email.element.value).toBe('123');
        expect(password.element.value).toBe('12345');
        await flush();
        expect(wrapper.find('button[type="submit"]').attributes('disabled')).toBe('disabled');
        wrapper.find('button[type="submit"]').trigger('click');
        await flush();
        expect(wrapper.element).toMatchSnapshot();
        expect(wrapper.find('.v-counter').text()).toBe('5');
        expect(login).not.toBeCalled();

        email.setValue('test@example.com');
        password.setValue('password');
        expect(email.element.value).toBe('test@example.com');
        expect(password.element.value).toBe('password');
        await flush();
        expect(wrapper.find('button[type="submit"]').attributes('disabled')).toBe(undefined);
        wrapper.find('button[type="submit"]').trigger('click');
        wrapper.find('button[type="submit"]').trigger('click');
        await flush();
        expect(wrapper.element).toMatchSnapshot();
        expect(wrapper.find('.v-counter').text()).toBe('8');
        expect(login).toBeCalledTimes(1);
    });
});
