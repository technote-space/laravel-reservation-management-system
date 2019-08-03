const state = {
    isActiveDrawer: true,
    isOpenDrawer: false,
    isActiveToolbar: true,
    sidebarItems: [
        { title: 'ダッシュボード', icon: 'mdi-view-dashboard', to: '/' },
        { title: '部屋', icon: 'mdi-bed-empty', to: '/rooms' },
        { title: '利用者', icon: 'mdi-human-male-male', to: '/guests' },
        { title: '予約', icon: 'mdi-calendar-month', to: '/reservations' },
//        { title: '設定', icon: 'mdi-settings', to: '/settings' },
//        { title: 'About', icon: 'mdi-help-box', to: '/about' },
    ],
    icons: [
        { icon: 'mdi-github-circle', url: 'https://github.com/technote-space/laravel-reservation-management-system' },
        { icon: 'mdi-twitter-circle', url: 'https://twitter.com/technote15' },
    ],
};

export default state;
