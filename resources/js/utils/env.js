import { get } from 'lodash';

const env = require('../../config/env.json');
export const getSetting = (key, defaultValue = undefined) => get(env.local, key, defaultValue);
export const getEnv = (key, defaultValue = undefined) => get(env[ getSetting('env') ], key, defaultValue);
