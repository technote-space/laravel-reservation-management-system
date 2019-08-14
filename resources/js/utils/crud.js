import List from '../components/templates/List';

const getListRouterName = model => `${ model }List`;

export const getListRouterSetting = model => ({
    path: `/${ model }`,
    name: getListRouterName(model),
    component: List,
    props: {
        targetModel: model,
    },
    meta: {},
});

export const getModelListRouter = model => ({ name: getListRouterName(model) });
