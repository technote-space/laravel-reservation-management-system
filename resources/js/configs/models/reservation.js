import moment from 'moment';
import { number, price, date } from '../../utils/processor';
import { getEnv } from '../../utils/env';

export default {
    slug: 'reservations',
    name: 'pages.reservation',
    icon: 'mdi-calendar-month',
    metaInfo: {},
    headers: [
        {
            text: 'id',
            value: 'id',
        },
        {
            text: 'checkin_datetime',
            value: 'start_datetime',
            processor: date,
        },
        {
            text: 'checkout_datetime',
            value: 'end_datetime',
            processor: date,
        },
        {
            text: 'number',
            value: 'detail.number',
            processor: number,
        },
        {
            text: 'name',
            value: 'detail.guest_name',
        },
        {
            text: 'phone',
            value: 'detail.guest_phone',
        },
        {
            text: 'room_name',
            value: 'detail.room_name',
        },
        {
            text: 'charge',
            value: 'charge',
            processor: price,
        },
        {
            text: 'payment',
            value: 'detail.payment',
            processor: price,
        },
    ],
    forms: [
        {
            name: 'reservations.guest_id',
            text: 'guest',
            value: 'guest_id',
            display: 'detail.guest_name',
            validate: {
                required: true,
                numeric: true,
            },
            type: 'search',
            search: 'guests:id:detail.name',
        },
        {
            name: 'reservations.room_id',
            text: 'room',
            value: 'room_id',
            display: 'room.name',
            validate: {
                required: true,
                numeric: true,
            },
            type: 'search',
            isAllowedEmptySearch: true,
            isAutoSearch: true,
            search: 'rooms:id:name',
        },
        {
            name: 'reservation_details.number',
            text: 'number',
            value: 'detail.number',
            validate: {
                required: true,
                numeric: true,
                max: 999,
                min: 1,
            },
            default: 2,
            icon: 'mdi-account-multiple',
        },
        {
            name: 'reservations.start_date',
            text: 'checkin_date',
            value: 'start_date_str',
            validate: {
                required: true,
                'date_format': 'yyyy-MM-dd',
            },
            type: 'date',
        },
        {
            name: 'reservations.nights',
            text: 'nights',
            value: 'nights',
            validate: {
                required: true,
                numeric: true,
                max: 0 < getEnv('number.reservation') ? getEnv('number.reservation') : 999,
                min: 1,
            },
            default: 2,
            icon: 'mdi-home',
            nameFilter: function () {
                return 'reservations.end_date';
            },
            valueFilter: function (value, inputs) {
                return moment(inputs[ 'reservations.start_date' ]).add(Number(value) - 1, 'days').format('YYYY-MM-DD');
            },
        },
        {
            name: 'reservations.checkout',
            text: 'checkout_time',
            value: 'checkout',
            validate: {
                required: true,
                'date_format': 'HH:mm',
            },
            default: '10:00',
            type: 'time',
        },
        {
            name: 'reservation_details.payment',
            text: 'payment',
            value: 'detail.payment',
            validate: {
                required: false,
                numeric: true,
                min: 0,
            },
            icon: 'mdi-cash-usd',
        },
    ],
};
