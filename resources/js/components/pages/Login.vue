<template>
    <v-container fluid>
        <v-layout justify-center>
            Login
        </v-layout>
    </v-container>
</template>

<script>
    import { mapActions } from 'vuex';

    export default {
        created () {
            this.offDrawer();
            this.offToolbar();
        },
        methods: {
            ...mapActions({
                offDrawer: 'common/offDrawer',
                offToolbar: 'common/offToolbar',
            }),
        },
    };
</script>
