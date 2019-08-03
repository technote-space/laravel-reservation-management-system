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

export const getListEntryPoint = state => getModel(state, STATE_MODE_TARGET) + '?page=' + getCurrentPage(state, STATE_MODE_TARGET);
export const isRequiredFetchList = state => getModel(state, STATE_MODE_TARGET) !== getModel(state, STATE_MODE_CURRENT) || getCurrentPage(state, STATE_MODE_TARGET) !== getCurrentPage(state, STATE_MODE_CURRENT);
export const isLoadedList = state => !isRequiredFetchList(state);
export const getListItems = state => isLoadedList(state) ? getList(state, STATE_MODE_CURRENT).data : [];
export const getTotalCount = state => isLoadedList(state) ? getList(state, STATE_MODE_CURRENT).total : 0;
export const getTotalPage = state => isLoadedList(state) ? getList(state, STATE_MODE_CURRENT).totalPage : 0;
export const isValidPagination = state => isLoadedList(state);
export const hasNextPage = state => isValidPagination(state) && getCurrentPage(state, STATE_MODE_CURRENT) < getTotalPage(state);
export const hasPrevPage = state => isValidPagination(state) && 1 < getCurrentPage(state, STATE_MODE_CURRENT);

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
