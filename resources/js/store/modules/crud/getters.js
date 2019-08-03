import { get, set } from 'lodash';
import { STATE_MODE_TARGET, STATE_MODE_CURRENT } from './constant';
import { getModelListRouter, getModelDetailRouter } from './utils';

export const getModel = (state, mode) => state[ mode ].model;
export const getTargetModel = state => getModel(state, STATE_MODE_TARGET);

const getList = (state, mode) => state[ mode ].list;
const getCurrentPage = (state, mode) => state[ mode ].list.page;
const getDetail = (state, mode) => state[ mode ].detail;
const getDetailCaches = state => getDetail(state, STATE_MODE_CURRENT).caches;
const getDetailId = (state, mode) => getDetail(state, mode).id;
const isValidDetailId = (state, mode) => 0 < getDetailId(state, mode);
const isSameModel = state => getModel(state, STATE_MODE_TARGET) === getModel(state, STATE_MODE_CURRENT);
const isSamePage = state => getCurrentPage(state, STATE_MODE_TARGET) === getCurrentPage(state, STATE_MODE_CURRENT);
const process = (headers, data) => {
    const target = {};
    Object.keys(headers).forEach(key => {
        const path = headers[ key ].value;
        if (headers[ key ].processor && 'function' === typeof headers[ key ].processor) {
            set(target, path, headers[ key ].processor(get(data, path)));
        } else {
            set(target, path, get(data, path));
        }
    });
    return target;
};
const processList = (headers, list) => list.map(data => process(headers, data));

export const getListEntryPoint = state => getModel(state, STATE_MODE_TARGET) + '?page=' + getCurrentPage(state, STATE_MODE_TARGET);
export const isRequiredFetchList = state => !isSameModel(state) || !isSamePage(state);
export const getListHeaders = state => state.headers[ getModel(state, STATE_MODE_CURRENT) ];
export const getListItems = state => isSameModel(state) ? processList(getListHeaders(state), getList(state, STATE_MODE_CURRENT).data) : [];
export const getTotalCount = state => isSameModel(state) ? getList(state, STATE_MODE_CURRENT).total : 0;
export const getTotalPage = state => isSameModel(state) ? getList(state, STATE_MODE_CURRENT).totalPage : 1;
export const getPage = state => getList(state, STATE_MODE_TARGET).page || 1;
export const getPerPage = state => isSameModel(state) ? getList(state, STATE_MODE_CURRENT).perPage : 15;

export const getDetailEntryPoint = state => getModel(state, STATE_MODE_TARGET) + '/' + getDetailId(state, STATE_MODE_TARGET);
export const hasDetailCache = state =>
    isValidDetailId(state, STATE_MODE_TARGET) &&
    getModel(state, STATE_MODE_TARGET) === getModel(state, STATE_MODE_CURRENT) &&
    getDetailId(state, STATE_MODE_TARGET) in getDetailCaches(state);
export const isRequiredFetchDetail = state => !hasDetailCache(state);
export const getDetailData = state => hasDetailCache(state) ? getDetailCaches(state)[ getDetailId(state, STATE_MODE_TARGET) ] : null;

export const getListRouter = state => getModelListRouter(getModel(state, STATE_MODE_TARGET));
export const getDetailRouter = state => getModelDetailRouter(getModel(state, STATE_MODE_TARGET), getDetailId(state, STATE_MODE_TARGET));
export const isEditable = state => (model, id) => model === getModel(state, STATE_MODE_CURRENT) && id === getDetailId(state, STATE_MODE_CURRENT);
