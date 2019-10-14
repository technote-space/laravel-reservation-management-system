<template>
    <ValidationObserver
        @submit.prevent="login"
        v-slot="{ invalid }"
        class="text-center"
        tag="form"
    >
        <ValidationProvider
            v-slot="{ errors }"
            :name="$t('validations.attributes.email')"
            rules="required|email"
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
            :name="$t('validations.attributes.password')"
        >
            <v-text-field
                v-model="loginForm.password"
                :append-icon="passwordIcon"
                :error-messages="errors"
                :hint="$t('messages.password_hint', {min: 8})"
                :label="$t('validations.attributes.password')"
                :type="passwordType"
                @click:append="passwordVisibility = !passwordVisibility"
                counter
                required
            />
        </ValidationProvider>
        <v-btn
            @click="login"
            :disabled="invalid && !submitting"
            class="mt-4"
            type="submit"
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
