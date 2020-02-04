<template>
    <v-container
        fluid
        fill-height
    >
        <v-row
            align="center"
            justify="center"
        >
            <v-card
                raised
                class="pa-6"
            >
                <v-card-text>
                    <LoginForm />
                </v-card-text>
            </v-card>
        </v-row>
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
