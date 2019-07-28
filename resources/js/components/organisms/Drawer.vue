<template>
    <v-navigation-drawer
        :value="isOpenDrawer"
        :clipped="$vuetify.breakpoint.mdAndUp"
        color="secondary"
        fixed
        temporary
        dark
        @input="setDrawerOpen"
    >
        <v-list
            dense
            nav
            class="py-0 my-4"
        >
            <v-list-item
                v-for="item in sidebarItems"
                :key="item.title"
                :to="item.to"
            >
                <v-list-item-icon>
                    <v-icon>{{ item.icon }}</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>{{ item.title }}</v-list-item-title>
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
                sidebarItems: 'common/getSidebarItems',
                isOpenDrawer: 'common/isOpenDrawer',
            }),
        },
        methods: {
            ...mapActions({
                setDrawerOpen: 'common/setDrawerOpen',
            }),
        },
    };
</script>
