import { get } from 'lodash';
import sns from '../configs/sns';
import menu from '../configs/menu';
import models from '../configs/models';

export const getSns = () => sns;
export const getMenu = () => menu;
export const getModels = () => models;

const getModel = model => get(getModels(), model);
export const getModelName = () => model => getModel(model).name;
export const getModelIcon = () => model => getModel(model).icon;
export const getModelHeaders = () => model => getModel(model).headers;
export const getMetaInfo = () => model => getModel(model).metaInfo;
