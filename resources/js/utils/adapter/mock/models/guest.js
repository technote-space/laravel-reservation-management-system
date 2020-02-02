import store from '../../../../store';
import detail from './guest-detail';

export default item => {
    return Object.assign({}, item, {
        detail: detail(store.getters[ 'adapter/search' ]('guestDetails', detail => detail.guest_id === item.id)),
    });
};
