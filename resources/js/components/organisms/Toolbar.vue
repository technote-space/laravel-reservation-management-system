<template>
    <v-app-bar
        :value="isActiveToolbar"
        app
        dark
        color="primary"
    >
        <v-app-bar-nav-icon
            v-if="isActiveDrawer"
            @click.stop="openDrawer"
        />
        <v-toolbar-title class="mr-5 align-center hidden-sm-and-down">
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
            }),
        },
        methods: {
            ...mapActions({
                openDrawer: 'common/openDrawer',
            }),
        },
    };
</script>
