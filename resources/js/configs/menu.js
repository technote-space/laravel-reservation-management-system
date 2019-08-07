import models from './models';

export default [
    { title: 'pages.dashboard', icon: 'mdi-view-dashboard', to: '/' },
].concat(Object.keys(models).map(model => ({
    title: models[ model ].name,
    icon: models[ model ].icon,
    to: `/${ model }`,
}))).concat([
    //        { title: 'pages.settings', icon: 'mdi-settings', to: '/settings' },
    //        { title: 'pages.about', icon: 'mdi-help-box', to: '/about' },
]);
