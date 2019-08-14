import * as getters from './getters';
import common from './modules/common';
import auth from './modules/auth';
import crud from './modules/crud';
import loading from './modules/loading';
import mock from './modules/mock';

export default () => {
    const modules = {
        common,
        auth,
        crud,
        loading,
    };

    if ('local' === process.env.NODE_ENV || 'test' === process.env.NODE_ENV) {
        modules.mock = mock;
    }
    return {
        getters,
        modules,
        strict: 'production' !== process.env.NODE_ENV && 'local' !== process.env.NODE_ENV,
        plugins: [],
    };
};
