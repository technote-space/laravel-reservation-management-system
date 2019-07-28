import { LOCK_PROCESS, UNLOCK_PROCESS } from './constant';

const mutations = {
    [ LOCK_PROCESS ] (state, payload) {
        state.processes = { ...state.processes, [ payload.key ]: true };
        if (payload.overlay) {
            state.overlayProcesses = { ...state.overlayProcesses, [ payload.key ]: true };
        }
    },
    [ UNLOCK_PROCESS ] (state, payload) {
        state.processes = { ...state.processes };
        delete state.processes[ payload.key ];
        state.overlayProcesses = { ...state.overlayProcesses };
        delete state.overlayProcesses[ payload.key ];
    },
};

export default mutations;
