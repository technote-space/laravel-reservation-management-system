import mock from './mock';

export default (method, url, data = undefined) => {
    if ('local' === process.env.NODE_ENV) {
        return mock(method.toLocaleString(), url, data);
    } else {
        return window.axios[ method.toLocaleString() ]('/api/' + url, data);
    }
}
