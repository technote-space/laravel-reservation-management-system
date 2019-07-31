const state = {
    isActiveDrawer: true,
    isOpenDrawer: false,
    isActiveToolbar: true,
    sidebarItems: [
        { title: 'ダッシュボード', icon: 'mdi-view-dashboard', to: '/' },
        { title: '部屋', icon: 'mdi-bed-empty', to: '/room' },
        { title: '利用者', icon: 'mdi-human-male-male', to: '/guest' },
        { title: '予約', icon: 'mdi-calendar-month', to: '/reservation' },
        { title: '設定', icon: 'mdi-settings', to: '/setting' },
        { title: 'About', icon: 'mdi-help-box', to: '/about' },
    ],
    icons: [
        { icon: 'mdi-github-circle', url: 'https://github.com/technote-space/laravel-reservation-management-system' },
        { icon: 'mdi-twitter-circle', url: 'https://twitter.com/technote15' },
    ],
};

export default state;
