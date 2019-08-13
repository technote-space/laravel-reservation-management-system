require('moment-timezone');
import moment from 'moment';
import { extendMoment } from 'moment-range';
import { getTimezone } from '../store/getters';

moment.tz.setDefault(getTimezone());
extendMoment(moment);
