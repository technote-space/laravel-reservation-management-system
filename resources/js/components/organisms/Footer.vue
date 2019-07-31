<template>
    <v-footer
        dark
        color="primary"
        padless
        absolute
    >
        <v-layout
            justify-center
            wrap
        >
            <v-btn
                v-for="icon in icons"
                :key="icon.icon"
                class="mx-4 white--text"
                icon
                :href="icon.url"
                target="_blank"
            >
                <v-icon size="24px">
                    {{ icon.icon }}
                </v-icon>
            </v-btn>
            <v-flex
                secondary
                lighten-2
                py-4
                text-center
                white--text
                xs12
            >
                {{ new Date().getFullYear() }} — <strong>{{ title }}</strong>
            </v-flex>
        </v-layout>
    </v-footer>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default {
        computed: {
            ...mapGetters({
                title: 'getTitle',
                icons: 'common/getIcons',
            }),
        },
    };
</script>
