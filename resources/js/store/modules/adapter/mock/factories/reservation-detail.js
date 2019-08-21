import base from './base';

export default base('reservation-detail', () => ({
    reservation_id: null,
    number: null,
    payment: null,
    room_name: null,
    guest_name: null,
    guest_name_kana: null,
    guest_zip_code: null,
    guest_phone: null,
}));
