import mock from './mock';

export default (method, url, data = undefined) => {
    if ('local' === process.env.NODE_ENV) {
        return mock(getMethod(method), url, data);
    } else {
        return window.axios[ getMethod(method) ]('/api/' + url, getData(method, data));
    }
};

const getMethod = method => method.toLocaleString();

const getData = (method, data) => 'object' === typeof data && 'get' === getMethod(method) ? ({ params: data }) : data;
