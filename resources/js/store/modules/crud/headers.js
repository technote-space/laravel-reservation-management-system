import moment from 'moment';

const date = value => moment(value).format('YYYY/MM/DD HH:mm');
const number = value => value + '人';
const price = value => value + '円';

export default {
    guests: [
        {
            text: 'ID',
            value: 'id',
        },
        {
            text: '名前',
            value: 'detail.name',
        },
        {
            text: 'カナ名',
            value: 'detail.name_kana',
        },
        {
            text: '郵便番号',
            value: 'detail.zip_code',
        },
        {
            text: '住所',
            value: 'detail.address',
        },
        {
            text: '電話番号',
            value: 'detail.phone',
        },
    ],
    rooms: [
        {
            text: 'ID',
            value: 'id',
        },
        {
            text: '部屋名',
            value: 'name',
        },
        {
            text: '最大人数',
            value: 'number',
            processor: number,
        },
        {
            text: '一泊料金',
            value: 'price',
            processor: price,
        },
    ],
    reservations: [
        {
            text: 'ID',
            value: 'id',
        },
        {
            text: '開始日時',
            value: 'start_datetime',
            processor: date,
        },
        {
            text: '終了日時',
            value: 'end_datetime',
            processor: date,
        },
        {
            text: '人数',
            value: 'number',
            processor: number,
        },
        {
            text: '名前',
            value: 'guest.detail.name',
        },
        {
            text: '電話番号',
            value: 'guest.detail.phone',
        },
        {
            text: '部屋名',
            value: 'room.name',
        },
        {
            text: '金額',
            value: 'room.price',
            processor: price,
        },
    ],
};
