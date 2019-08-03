const getListRouterName = model => `${ model }List`;

const getDetailRouterName = model => `${ model }Detail`;

export const getListRouterSetting = (model, component) => ({
    path: `/${ model }`,
    name: getListRouterName(model),
    component,
    meta: {},
});

export const getDetailRouterSetting = (model, component) => ({
    path: `/${ model }/:id`,
    name: getDetailRouterName(model),
    component,
    meta: {},
});

export const getModelListRouter = model => ({ name: getListRouterName(model) });

export const getModelDetailRouter = (model, id) => ({ name: getDetailRouterName(model), params: { id } });
