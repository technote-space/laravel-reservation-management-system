import { camelCase } from 'lodash';
import { CREATE, UPDATE, DELETE, LOGIN, LOGOUT } from './constant';

const mutations = {
    [ CREATE ] (state, { model, data }) {
        state.items = Object.assign({}, state.items);

        const camel = camelCase(model);
        const item = state.factories[ camel ]().create(data[ model ]);
        state.items[ model ][ item.id ] = item;
        const foreignKey = model.replace(/e?s$/, '') + '_id';
        const foreignId = item.id;

        delete data[ model ];
        Object.keys(data).map(model => {
            const camel = camelCase(model);
            data[ model ][ foreignKey ] = foreignId;
            const item = state.factories[ camel ]().create(data[ model ]);
            state.items[ camel ][ item.id ] = item;
        });
    },
    [ UPDATE ] (state, { id, data }) {
        state.items = Object.assign({}, state.items);
        Object.keys(data).map(model => {
            const camel = camelCase(model);
            state.items[ camel ][ id ] = Object.assign({}, state.items[ camel ][ id ], data[ model ]);
        });
    },
    [ DELETE ] (state, { model, id }) {
        const camel = camelCase(model);
        state.items[ camel ] = Object.assign({}, state.items[ camel ]);
        delete state.items[ camel ][ id ];
    },
    [ LOGIN ] (state, { user }) {
        state.user = user;
    },
    [ LOGOUT ] (state) {
        state.user = null;
    },
};

export default mutations;
