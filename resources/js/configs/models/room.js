import { number, price } from '../../utils/processor';

export default {
    slug: 'rooms',
    name: 'pages.room',
    icon: 'mdi-bed-empty',
    metaInfo: {},
    headers: [
        {
            text: 'column.id',
            value: 'id',
        },
        {
            text: 'column.room_name',
            value: 'name',
        },
        {
            text: 'column.max_number',
            value: 'number',
            processor: number,
        },
        {
            text: 'column.price',
            value: 'price',
            processor: price,
        },
    ],
};
