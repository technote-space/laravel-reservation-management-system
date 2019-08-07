<template>
    <v-container
        fluid
        fill-height
    >
        <v-layout
            wrap
            justify-center
        >
            <v-card
                raised
                class="pa-6"
            >
                <v-card-text>
                    <LoginForm />
                </v-card-text>
            </v-card>
        </v-layout>
    </v-container>
</template>

<script>
    import LoginForm from '../organisms/LoginForm';

    export default {
        metaInfo () {
            return {
                title: this.$t('pages.login'),
            };
        },
        components: {
            LoginForm,
        },
    };
</script>
