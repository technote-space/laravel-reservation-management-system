import { CREATE, UPDATE, DELETE, LOGIN, LOGOUT } from './constant';

export const create = (context, { model, data }) => context.commit(CREATE, { model, data });
export const update = (context, { model, id, data }) => context.commit(UPDATE, { model, id, data });
export const destroy = (context, { model, id }) => context.commit(DELETE, { model, id });
export const login = (context, user) => context.commit(LOGIN, { user });
export const logout = context => context.commit(LOGOUT);
