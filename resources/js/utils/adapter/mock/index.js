import repository from './repository';

const sleep = msec => new Promise(resolve => setTimeout(resolve, msec));
export default async (method, url, data = undefined) => {
    await sleep(300);
    return await repository(method, url, data).then(data => ({
        response: {
            data,
        },
    })).catch(error => ({
        error,
    }));
}
