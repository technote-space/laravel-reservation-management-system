<template>
    <v-container fluid>
        <v-layout justify-center>
            Dashboard
        </v-layout>
    </v-container>
</template>

<script>
    import { mapActions } from 'vuex';

    export default {
        created () {
            this.onDrawer();
            this.onToolbar();
        },
        methods: {
            ...mapActions({
                onDrawer: 'common/onDrawer',
                onToolbar: 'common/onToolbar',
            }),
        },
    };
</script>
