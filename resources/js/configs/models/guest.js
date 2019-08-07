export default {
    slug: 'guests',
    name: 'pages.guest',
    icon: 'mdi-human-male-male',
    metaInfo: {},
    headers: [
        {
            text: 'column.id',
            value: 'id',
        },
        {
            text: 'column.name',
            value: 'detail.name',
        },
        {
            text: 'column.katakana',
            value: 'detail.name_kana',
        },
        {
            text: 'column.zip_code',
            value: 'detail.zip_code',
        },
        {
            text: 'column.address',
            value: 'detail.address',
        },
        {
            text: 'column.phone',
            value: 'detail.phone',
        },
    ],
    form: {
        name: {
            name: 'guest_details[name]',
            label: 'Name',
            validate: 'required|max:50',
        },
    },
};
