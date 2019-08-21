import vm from '../../app';
import { number, price, generator } from '../../utils/processor';
import Reservation from '../../components/organisms/Reservation';

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
        {
            text: 'last_year_sales',
            value: 'total_sales',
            processor: price,
        },
        {
            text: 'status',
            value: 'status',
            processor: generator(data => vm.$createElement(Reservation, {
                props: {
                    roomId: data.id,
                },
            }, null)),
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
