<template>
    <v-form @submit.prevent="login">
        <v-text-field
            v-model="loginForm.email"
            v-validate="'required|email'"
            :error-messages="errors.collect('email')"
            :label="$t('validations.attributes.email')"
            data-vv-name="email"
            data-vv-validate-on="blur"
            required
        />
        <v-text-field
            v-model="loginForm.password"
            v-validate="'required|min:8'"
            :error-messages="errors.collect('password')"
            :append-icon="passwordIcon"
            :type="passwordType"
            :label="$t('validations.attributes.password')"
            data-vv-name="password"
            data-vv-validate-on="blur"
            :hint="$t('messages.password_hint', {min: 8})"
            counter
            required
            @click:append="passwordVisibility = !passwordVisibility"
        />
        <v-btn
            class="mt-4"
            type="submit"
        >
            {{ $t('pages.login') }}
        </v-btn>
    </v-form>
</template>

<script>
    export default {
        data () {
            return {
                loginForm: {
                    email: '',
                    password: '',
                },
                passwordVisibility: false,
            };
        },
        computed: {
            passwordIcon: function () {
                return this.passwordVisibility ? 'visibility' : 'visibility_off';
            },
            passwordType: function () {
                return this.passwordVisibility ? 'text' : 'password';
            },
        },
        methods: {
            async login () {
                this.$validator.validateAll().then(async result => {
                    if (!result) {
                        return;
                    }
                    await this.$store.dispatch('auth/login', this.loginForm);
                });
            },
        },
    };
</script>
