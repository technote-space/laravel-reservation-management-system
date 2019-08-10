import { number, price } from '../../utils/processor';

export default {
    slug: 'rooms',
    name: 'pages.room',
    icon: 'mdi-bed-empty',
    metaInfo: {},
    headers: [
        {
            text: 'id',
            value: 'id',
        },
        {
            text: 'room_name',
            value: 'name',
        },
        {
            text: 'max_number',
            value: 'number',
            processor: number,
        },
        {
            text: 'price',
            value: 'price',
            processor: price,
        },
    ],
    forms: [
        {
            name: 'rooms.name',
            text: 'room_name',
            value: 'name',
            validate: {
                required: true,
                max: 50,
            },
        },
        {
            name: 'rooms.number',
            text: 'max_number',
            value: 'number',
            validate: {
                required: true,
                numeric: true,
                max: 999,
                min: 1,
            },
            default: 1,
        },
        {
            name: 'rooms.price',
            text: 'price',
            value: 'price',
            validate: {
                required: true,
                numeric: true,
                min: 0,
            },
        },
    ],
};
