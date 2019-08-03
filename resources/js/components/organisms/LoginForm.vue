<template>
    <v-form @submit.prevent="login">
        <v-text-field
            v-model="loginForm.email"
            v-validate="'required|email'"
            :error-messages="errors.collect('email')"
            label="E-mail"
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
            name="input-10-1"
            label="Password"
            data-vv-name="password"
            data-vv-validate-on="blur"
            hint="8文字以上"
            counter
            required
            @click:append="passwordVisibility = !passwordVisibility"
        />
        <v-btn
            class="mt-4"
            type="submit"
        >
            Login
        </v-btn>
    </v-form>
</template>

<script>
    import { mapGetters } from 'vuex';

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
            ...mapGetters({
                isAuthenticated: 'auth/isAuthenticated',
            }),
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
