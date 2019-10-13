<template>
    <ValidationObserver
        class="text-center"
        v-slot="{ invalid }"
    >
        <ValidationProvider
            v-slot="{ errors }"
            rules="required|email"
            name="email"
        >
            <v-text-field
                v-model="loginForm.email"
                :error-messages="errors"
                :label="$t('validations.attributes.email')"
                required
            />
        </ValidationProvider>
        <ValidationProvider
            v-slot="{ errors }"
            rules="required|min:8"
            name="password"
        >
            <v-text-field
                v-model="loginForm.password"
                :error-messages="errors"
                :append-icon="passwordIcon"
                :type="passwordType"
                :label="$t('validations.attributes.password')"
                :hint="$t('messages.password_hint', {min: 8})"
                @click:append="passwordVisibility = !passwordVisibility"
                counter
                required
            />
        </ValidationProvider>
        <v-btn
            class="mt-4"
            type="submit"
            :disabled="invalid && !submitting"
            @click="login"
        >
            {{ $t('pages.login') }}
        </v-btn>
    </ValidationObserver>
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
                submitting: false,
            };
        },
        computed: {
            passwordIcon () {
                return this.passwordVisibility ? 'visibility' : 'visibility_off';
            },
            passwordType () {
                return this.passwordVisibility ? 'text' : 'password';
            },
        },
        methods: {
            async login () {
                if (this.submitting) {
                    return;
                }
                this.submitting = true;
                await this.$store.dispatch('auth/login', this.loginForm);
                this.submitting = false;
            },
        },
    };
</script>
