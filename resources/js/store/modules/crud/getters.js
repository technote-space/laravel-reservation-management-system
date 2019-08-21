import { get, set } from 'lodash';
import { STATE_MODE_TARGET, STATE_MODE_CURRENT } from './constant';
import { getModelListRouter } from '../../../utils/crud';
import store from '../../index';

const getMode = (state, mode) => state[ mode ];
export const getModel = (state, mode) => getMode(state, mode).model;
export const getTargetModel = state => getModel(state, STATE_MODE_TARGET);

const getList = (state, mode) => getMode(state, mode).list;
const getCurrentPage = (state, mode) => getMode(state, mode).list.page;
const getDetail = (state, mode) => getMode(state, mode).detail;
const getDetailCaches = state => getDetail(state, STATE_MODE_CURRENT).caches;
const getDetailId = (state, mode) => getDetail(state, mode).id;
const isValidDetailId = (state, mode) => 0 < getDetailId(state, mode);
const isSameModel = state => getModel(state, STATE_MODE_TARGET) === getModel(state, STATE_MODE_CURRENT);
const isSamePage = state => getCurrentPage(state, STATE_MODE_TARGET) === getCurrentPage(state, STATE_MODE_CURRENT);
const process = (headers, data) => {
    const target = {};
    Object.keys(headers).forEach(key => {
        const path = headers[ key ].value;
        if ('action' === path) {
            return;
        }
        if (headers[ key ].processor && 'function' === typeof headers[ key ].processor) {
            set(target, path, headers[ key ].processor(get(data, path), path, data));
        } else {
            set(target, path, get(data, path));
        }
    });
    return target;
};
const processList = (headers, list) => list.map(data => process(headers, data));
const isRequiredRefresh = state => state.isRequiredRefresh;
const getModelHeaders = model => store.getters[ 'getModelHeaders' ](model);
const getHeaders = (state, mode) => {
    const model = getModel(state, mode);
    if (!model) {
        return [];
    }
    return getModelHeaders(model).concat([{ text: 'actions', value: 'action' }]);
};

export const isRequiredFetchList = state => isRequiredRefresh(state) || !isSameModel(state) || !isSamePage(state);
export const getListHeaders = state => getHeaders(state, STATE_MODE_TARGET);
export const getListItems = state => isSameModel(state) ? processList(getHeaders(state, STATE_MODE_CURRENT), getList(state, STATE_MODE_CURRENT).data) : [];
export const getTotalCount = state => isSameModel(state) ? getList(state, STATE_MODE_CURRENT).total : 0;
export const getTotalPage = state => isSameModel(state) ? getList(state, STATE_MODE_CURRENT).totalPage : 1;
export const getPage = state => getList(state, STATE_MODE_TARGET).page || 1;
export const getPerPage = state => isSameModel(state) ? getList(state, STATE_MODE_CURRENT).perPage : 15;

export const hasDetailCache = state =>
    !isRequiredRefresh(state) &&
    isValidDetailId(state, STATE_MODE_TARGET) &&
    getModel(state, STATE_MODE_TARGET) === getModel(state, STATE_MODE_CURRENT) &&
    getDetailId(state, STATE_MODE_TARGET) in getDetailCaches(state);
export const isRequiredFetchDetail = state => !hasDetailCache(state);
export const getDetailData = state => hasDetailCache(state) ? getDetailCaches(state)[ getDetailId(state, STATE_MODE_TARGET) ] : null;

export const getListEntryPoint = state => getModel(state, STATE_MODE_TARGET) + '?page=' + getCurrentPage(state, STATE_MODE_TARGET);
export const getDetailEntryPoint = state => getModel(state, STATE_MODE_TARGET) + '/' + getDetailId(state, STATE_MODE_TARGET);
export const getCreateMethod = () => 'post';
export const getCreateEntryPoint = state => getModel(state, STATE_MODE_TARGET);
export const getEditMethod = () => 'patch';
export const getEditEntryPoint = state => getModel(state, STATE_MODE_TARGET) + '/' + getDetailId(state, STATE_MODE_TARGET);
export const getDeleteMethod = () => 'delete';
export const getDeleteEntryPoint = state => getModel(state, STATE_MODE_TARGET) + '/' + getDetailId(state, STATE_MODE_TARGET);

export const getSearchEntryPoint = () => model => model;
export const getReservationCheckEntryPoint = () => 'reservations/check';

export const getListRouter = state => getModelListRouter(getModel(state, STATE_MODE_TARGET));
export const isCreatable = state => model => model === getModel(state, STATE_MODE_CURRENT);
export const isEditable = state => (model, id) => model === getModel(state, STATE_MODE_CURRENT) && id === getDetailId(state, STATE_MODE_CURRENT);
export const isDeletable = state => (model, id) => model === getModel(state, STATE_MODE_CURRENT) && id === getDetailId(state, STATE_MODE_CURRENT);
