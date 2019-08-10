require('moment-timezone');
import moment from 'moment';
import { getTimezone } from '../store/getters';

moment.tz.setDefault(getTimezone());
