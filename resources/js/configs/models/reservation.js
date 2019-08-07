import { number, price, date } from '../../utils/processor';

export default {
    slug: 'reservations',
    name: 'pages.reservation',
    icon: 'mdi-calendar-month',
    metaInfo: {},
    headers: [
        {
            text: 'column.id',
            value: 'id',
        },
        {
            text: 'column.start_datetime',
            value: 'start_datetime',
            processor: date,
        },
        {
            text: 'column.end_datetime',
            value: 'end_datetime',
            processor: date,
        },
        {
            text: 'column.number',
            value: 'number',
            processor: number,
        },
        {
            text: 'column.name',
            value: 'guest.detail.name',
        },
        {
            text: 'column.phone',
            value: 'guest.detail.phone',
        },
        {
            text: 'column.room_name',
            value: 'room.name',
        },
        {
            text: 'column.price',
            value: 'room.price',
            processor: price,
        },
    ],
};
