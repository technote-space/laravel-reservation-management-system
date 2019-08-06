import targets from './targets';
import name from './name';
import icon from './icon';
import metaInfo from './meta-info';
import headers from './headers';

export default Object.assign(...targets.map(model => ({
    [ model ]: {
        name: name[ model ],
        icon: icon[ model ],
        metaInfo: metaInfo[ model ],
        headers: headers[ model ],
    },
})));
