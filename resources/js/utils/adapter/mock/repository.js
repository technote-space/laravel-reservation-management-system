import auth from './repositories/auth';
import summary from './repositories/summary';
import reservation from './repositories/reservation';
import crud from './repositories/crud';

export default async (method, url, data = undefined) => {
    const split = url.split('?');
    const matches = split[ 0 ].match(/^(.+?)(\/(\d+))?$/);
    if (matches) {
        const model = matches[ 1 ];
        const id = matches[ 3 ] ? matches[ 3 ] - 0 : null;
        const query = (2 <= split.length ? split[ 1 ] : '').split('&');
        for (const repository of [auth, summary, reservation, crud]) {
            const result = await repository(data, model, method, id, query);
            if (undefined !== result) {
                return result;
            }
        }
    }

    throw Error('Not Found');
};
