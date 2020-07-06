<template>
    <v-app>
        <Toolbar />
        <v-main
            app
            class="mb-5"
        >
            <Drawer />
            <RouterView />
        </v-main>
        <Footer />
        <Loading />
    </v-app>
</template>

<script>
    import Toolbar from './organisms/Toolbar';
    import Drawer from './organisms/Drawer';
    import Footer from './organisms/Footer';
    import Loading from './organisms/Loading';

    export default {
        components: {
            Toolbar,
            Drawer,
            Footer,
            Loading,
        },
    };
</script>
