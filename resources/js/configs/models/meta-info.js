import targets from './targets';
import name from './name';

export default Object.assign(...targets.map(model => ({
    [ model ]: {
        title: name[ model ],
    },
})));
