import store from '../../../../store';

export default item => {
    const reservation_id = item.reservation_id;
    const reservation = store.getters[ 'adapter/getItem' ]('reservations', reservation_id);
    const room = store.getters[ 'adapter/getItem' ]('rooms', reservation.room_id);
    const guest = store.getters[ 'adapter/getItem' ]('guestDetails', reservation.guest_id);
    return Object.assign({}, item, {
        room_name: room.name,
        guest_name: guest.name,
        guest_name_kana: guest.name_kana,
        guest_zip_code: guest.zip_code,
        guest_address: guest.address,
        guest_phone: guest.phone,
    });
}
