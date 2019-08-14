import * as getters from './getters';
import common from './modules/common';
import auth from './modules/auth';
import crud from './modules/crud';
import loading from './modules/loading';
import adapter from './modules/adapter';

export default () => ({
    getters,
    modules: {
        common,
        auth,
        crud,
        loading,
        adapter,
    },
    strict: IS_STRICT,
    plugins: [],
});
