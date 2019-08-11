import { CREATE, UPDATE, DELETE, LOGIN, LOGOUT } from './constant';

const mutations = {
    [ CREATE ] (state, { model, data }) {
        state.items[ model ] = Object.assign({}, state.items[ model ]);
        const item = state.factories[ model ]().create(data[ model ]);
        state.items[ model ][ item.id ] = item;
    },
    [ UPDATE ] (state, { model, id, data }) {
        if (id in state.items[ model ]) {
            state.items[ model ] = Object.assign({}, state.items[ model ]);
            state.items[ model ][ id ] = Object.assign({}, state.items[ model ][ id ], data[ model ]);
        }
    },
    [ DELETE ] (state, { model, id }) {
        if (id in state.items[ model ]) {
            state.items[ model ] = Object.assign({}, state.items[ model ]);
            delete state.items[ model ][ id ];
        }
    },
    [ LOGIN ] (state, { user }) {
        state.user = user;
    },
    [ LOGOUT ] (state) {
        state.user = null;
    },
};

export default mutations;
