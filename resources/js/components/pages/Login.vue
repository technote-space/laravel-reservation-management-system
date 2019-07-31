<template>
    <v-container fluid>
        <v-layout justify-center>
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
        metaInfo: {
            title: 'Login',
        },
        components: {
            LoginForm,
        },
    };
</script>
