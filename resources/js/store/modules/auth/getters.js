export const isInitialized = state => state.initialized;
export const getUser = state => state.user;
export const getUserName = state => isAuthenticated(state) ? state.user.name : '';
export const isAuthenticated = state => !!state.user;
export const getBackTo = state => state.backTo;
