const getData = (method, data) => 'object' === typeof data && 'get' === method ? ({ params: data }) : data;
export default (method, url, data) => window.axios[ method ]('/api/' + url, getData(method, data));
