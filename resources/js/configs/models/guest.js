export default {
    slug: 'guests',
    name: 'pages.guest',
    icon: 'mdi-human-male-male',
    metaInfo: {},
    headers: [
        {
            text: 'id',
            value: 'id',
        },
        {
            text: 'name',
            value: 'detail.name',
        },
        {
            text: 'katakana',
            value: 'detail.name_kana',
        },
        {
            text: 'zip_code',
            value: 'detail.zip_code',
        },
        {
            text: 'address',
            value: 'detail.address',
        },
        {
            text: 'phone',
            value: 'detail.phone',
        },
    ],
    forms: [
        {
            name: 'guest_details.name',
            text: 'name',
            value: 'detail.name',
            validate: {
                required: true,
                max: 50,
            },
        },
        {
            name: 'guest_details.name_kana',
            text: 'katakana',
            value: 'detail.name_kana',
            validate: {
                required: true,
                max: 50,
                // eslint-disable-next-line no-irregular-whitespace
                regex: /^[ァ-ヴー・\s　]+$/,
            },
        },
        {
            name: 'guest_details.zip_code',
            text: 'zip_code',
            value: 'detail.zip_code',
            validate: {
                required: true,
                regex: /^[0-9]{3}-[0-9]{4}$|^[0-9]{3}-[0-9]{2}$|^[0-9]{3}$|^[0-9]{5}$|^[0-9]{7}$/,
            },
            hint: 'hint.zip_code',
            icon: 'mdi-map-marker',
        },
        {
            name: 'guest_details.address',
            text: 'address',
            value: 'detail.address',
            validate: {
                required: true,
                max: 100,
            },
            icon: 'mdi-map-marker',
        },
        {
            name: 'guest_details.phone',
            text: 'phone',
            value: 'detail.phone',
            validate: {
                required: true,
                regex: /^[0-9]{2,4}-?[0-9]{2,4}-?[0-9]{3,4}$/,
            },
            hint: 'hint.phone',
            icon: 'mdi-phone',
        },
    ],
};
