const adapter = require(ADAPTER).default;
const getMethod = method => method.toLocaleString();

export default (method, url, data = undefined) => adapter(getMethod(method), url, data);
