<template>
    <v-footer
        app
        dark
        color="primary"
        padless
        absolute
    >
        <v-row
            justify="center"
        >
            <v-btn
                v-for="item in sns"
                :key="item.icon"
                :href="item.url"
                class="mx-4 white--text"
                icon
                target="_blank"
            >
                <v-icon size="24px">
                    {{ item.icon }}
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
                {{ year }} — <strong>{{ $t('title') }}</strong>
            </v-flex>
        </v-row>
    </v-footer>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default {
        computed: {
            ...mapGetters({
                sns: 'getSns',
            }),
            year () {
                return new Date().getFullYear();
            },
        },
    };
</script>
