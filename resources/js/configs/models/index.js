import name from './name';
import icon from './icon';
import headers from './headers';

export default Object.assign(...[
    'rooms',
    'guests',
    'reservations',
].map(model => ({
    [ model ]: {
        name: name[ model ],
        icon: icon[ model ],
        headers: headers[ model ],
    },
})));
