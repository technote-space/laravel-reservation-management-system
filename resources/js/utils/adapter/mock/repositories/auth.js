import store from '../../../../store';

const getUser = () => store.getters[ 'adapter/getUser' ];

const login = async data => {
    const user = store.getters[ 'adapter/search' ]('admins', item => item.email === data.email && item.password === data.password);
    if (user) {
        await store.dispatch('adapter/login', user, { root: true });
    } else {
        throw Error('Failed to login');
    }
    return user;
};

const logout = async () => {
    await store.dispatch('adapter/logout', undefined, { root: true });
    return false;
};

export default async (data, model) => {
    if ('user' === model) {
        return getUser();
    }

    if ('login' === model) {
        return login(data);
    }

    if ('logout' === model) {
        return logout();
    }
};
