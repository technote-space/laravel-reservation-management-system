import { number, price, date } from '../../utils/processor';

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
            text: 'start_datetime',
            value: 'start_datetime',
            processor: date,
        },
        {
            text: 'end_datetime',
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
            name: 'reservations.start_date',
            text: 'start_date',
            value: 'start_date_str',
            validate: {
                required: true,
                'date_format': 'yyyy-MM-dd',
            },
            type: 'date',
            checkKey: 'startDate',
        },
        {
            name: 'reservations.end_date',
            text: 'end_date',
            value: 'end_date_str',
            validate: {
                required: true,
                'date_format': 'yyyy-MM-dd',
            },
            type: 'date',
            checkKey: 'endDate',
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
        },
    ],
};
