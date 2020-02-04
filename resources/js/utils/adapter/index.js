export const getMethod = method => method.toLowerCase();
export const adapter = (method, url, data = undefined) => require(ADAPTER).default(getMethod(method), url, data);
