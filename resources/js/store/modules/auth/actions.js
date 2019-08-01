import router from '../../../router';
import { SET_INIT, SET_USER, SET_BACK_TO } from './constant';
import { isInitialized, isAuthenticated, getBackTo } from './getters';
import { apiAccess } from '../../../utils/api';

/**
 * @param context
 * @param name
 * @param data
 * @param options
 * @param options.method
 */
const access = async (context, name, data = undefined, options = { method: 'post' }) => {
    context.dispatch('loading/onLoading', 'auth/' + name, { root: true });
    const method = options.method || 'post';
    delete options.method;
    await apiAccess(method, name, Object.assign({
        data,
        succeeded: async response => {
            const userData = response.data || null;
            if (null !== userData && ('object' !== typeof userData || !userData[ 'id' ])) {
                if ('user' !== name) {
                    await user(context);
                }
                return;
            }
            context.commit(SET_USER, userData);
            await checkAuth(context, { to: router.currentRoute, next: location => location ? router.push(location) : null });
        },
        always: () => {
            context.dispatch('loading/offLoading', 'auth/' + name, { root: true });
        },
    }, options));
};

/**
 * @param context
 * @param data
 */
export const login = async (context, data) => {
    await access(context, 'login', data);
};

/**
 * @param context
 */
export const logout = async context => {
    await access(context, 'logout');
};

/**
 * @param context
 */
export const user = async context => {
    await access(context, 'user', undefined, { method: 'get' });
};

/**
 * @param context
 * @param backTo
 * @returns {*}
 */
export const setBackTo = (context, backTo) => context.commit(SET_BACK_TO, backTo);

/**
 * @param context
 * @returns {*}
 */
export const initialized = context => context.commit(SET_INIT);

/**
 * @param context
 * @param to
 * @param next
 */
export const checkAuth = async (context, { to, next }) => {
    if (!isInitialized(context.state)) {
        initialized(context);
        await user(context);
    }
    if (to.meta) {
        if (true === to.meta.auth || undefined === to.meta.auth) {
            if (!isAuthenticated(context.state)) {
                setBackTo(context, to.fullPath);
                next({ path: '/login' });
                return;
            }
        } else if (false === to.meta.auth) {
            if (isAuthenticated(context.state)) {
                const backTo = getBackTo(context.state);
                if (backTo) {
                    setBackTo(context, null);
                    if (/^\//.test(backTo)) {
                        next({ path: backTo });
                        return;
                    }
                }
                next({ path: '/' });
                return;
            }
        }
    }
    next();
};
