import repository from './repository';
import { getSetting } from '../../env';

const sleep = msec => new Promise(resolve => setTimeout(resolve, msec));
const getSleepMs = method => getSetting('sleep.' + method, getSetting('sleep.default', 500));
export default async(method, url, data = undefined) => {
    await sleep(getSleepMs(method));
    return await repository(method, url, data).then(data => ({
        response: {
            data,
        },
    })).catch(error => ({
        error,
    }));
}
