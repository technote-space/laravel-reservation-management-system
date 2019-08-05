import models from './models';

export default [
    { title: 'ダッシュボード', icon: 'mdi-view-dashboard', to: '/' },
].concat(Object.keys(models).map(model => ({
    title: models[ model ].name,
    icon: models[ model ].icon,
    to: `/${ model }`,
}))).concat([
    //        { title: '設定', icon: 'mdi-settings', to: '/settings' },
    //        { title: 'About', icon: 'mdi-help-box', to: '/about' },
]);
