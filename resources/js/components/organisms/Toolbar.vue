<template>
    <v-app-bar
        v-if="isActiveToolbar"
        app
        dark
        color="primary"
    >
        <v-app-bar-nav-icon
            v-if="isAuthenticated && isActiveDrawer"
            @click.stop="openDrawer"
        />
        <v-toolbar-title class="mr-5 align-center">
            <router-link
                to="/"
                tag="span"
            >
                <span class="title">{{ title }}</span>
            </router-link>
        </v-toolbar-title>
    </v-app-bar>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';

    export default {
        computed: {
            ...mapGetters({
                title: 'getTitle',
                isActiveToolbar: 'common/isActiveToolbar',
                isActiveDrawer: 'common/isActiveDrawer',
                isAuthenticated: 'auth/isAuthenticated',
            }),
        },
        methods: {
            ...mapActions({
                openDrawer: 'common/openDrawer',
            }),
        },
    };
</script>
