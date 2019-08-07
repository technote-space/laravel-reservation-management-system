export default {
    title: 'Reservation System',
    pages: {
        dashboard: 'Dashboard',
        guest: 'Guest',
        reservation: 'Reservation',
        room: 'Room',
        login: 'Login',
        logout: 'Logout',
        'not_found': 'Not Found',
    },
    column: {
        id: 'ID',
        name: 'Name',
        'room_name': 'Room Name',
        katakana: 'Katakana',
        email: 'E-Mail',
        password: 'Password',
        price: 'Price',
        number: 'Number',
        'max_number': 'Max Number',
        'zip_code': 'ZIP Code',
        address: 'Address',
        phone: 'Phone',
        'start_datetime': 'Start Datetime',
        'end_datetime': 'End Datetime',
        actions: 'Actions',
    },
    validations: {
        attributes: {
            email: 'E-Mail',
            password: 'Password',
        },
    },
    loading: {},
    messages: {
        'password_hint': 'At least {min} characters',
        'delete_item': 'Are you sure you want to delete this item?',
    },
    unit: {
        number: '1 person | {value} people',
        price: '{value} yen',
    },
};