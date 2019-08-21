<template>
    <v-navigation-drawer
        v-if="isAuthenticated"
        :value="isOpenDrawer"
        @input="setDrawerOpen"
        color="secondary"
        fixed
        temporary
        dark
    >
        <v-list
            dense
            nav
            class="py-0 my-4"
        >
            <v-list-item two-line>
                <v-list-item-avatar>
                    <img src="https://randomuser.me/api/portraits/men/81.jpg">
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title>{{ userName }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-divider />

            <v-list-item
                v-for="item in menu"
                :key="item.title"
                :to="item.to"
                active-class="amber--text"
            >
                <v-list-item-icon>
                    <v-icon>{{ item.icon }}</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>{{ $t(item.title) }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item
                @click.stop="logout"
            >
                <v-list-item-icon>
                    <v-icon>mdi-logout</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>{{ $t('pages.logout') }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';

    export default {
        computed: {
            ...mapGetters({
                menu: 'getMenu',
                isOpenDrawer: 'common/isOpenDrawer',
                isAuthenticated: 'auth/isAuthenticated',
                userName: 'auth/getUserName',
            }),
        },
        methods: {
            ...mapActions({
                setDrawerOpen: 'common/setDrawerOpen',
                logout: 'auth/logout',
            }),
            async logout () {
                this.setDrawerOpen(false);
                await this.$store.dispatch('auth/logout');
            },
        },
    };
</script>
