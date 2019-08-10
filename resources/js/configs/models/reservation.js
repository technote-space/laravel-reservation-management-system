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
            text: 'start_date',
            value: 'start_datetime',
            processor: date,
        },
        {
            text: 'end_date',
            value: 'end_datetime',
            processor: date,
        },
        {
            text: 'number',
            value: 'number',
            processor: number,
        },
        {
            text: 'name',
            value: 'guest.detail.name',
        },
        {
            text: 'phone',
            value: 'guest.detail.phone',
        },
        {
            text: 'room_name',
            value: 'room.name',
        },
        {
            text: 'price',
            value: 'room.price',
            processor: price,
        },
    ],
    forms: [
        {
            name: 'reservations.guest_id',
            text: 'guest',
            value: 'guest_id',
            display: 'guest.detail.name',
            validate: {
                required: true,
                numeric: true,
            },
            type: 'search',
            search: 'guests:detail.id:detail.name',
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
            name: 'reservations.number',
            text: 'number',
            value: 'number',
            validate: {
                required: true,
                numeric: true,
                max: 999,
                min: 1,
            },
            default: 1,
        },
    ],
};
