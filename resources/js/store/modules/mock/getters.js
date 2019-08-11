import { get, orderBy, first, find, filter } from 'lodash';

export const getAll = state => model => model in state.items ? state.items[ model ] : {};
export const getAllArray = state => model => Object.values(getAll(state)(model)).reverse();
export const getItem = state => (model, id) => get(getAll(state)(model), id, null);
export const getLastItem = state => model => first(orderBy(getAllArray(state)(model), ['id'], 'desc'));
export const getUser = state => state.user;
export const isExists = state => (model, id) => id in getAll(state)(model);
export const search = state => (model, predicate) => find(getAllArray(state)(model), predicate) || null;
export const searchAll = state => (model, predicate) => filter(getAllArray(state)(model), predicate);
