<template>
    <v-text-field
        :label="label"
        :value="value"
        :hint="hint"
        :prepend-icon="prependIcon"
        persistent-hint
        :error-messages="validateErrors"
        @input="val => $emit('input', val)"
    />
</template>

<script>
    export default {
        props: {
            label: {
                type: String,
                required: true,
            },
            value: {
                validator: prop => null === prop || 'string' === typeof prop || 'number' === typeof prop,
                required: true,
            },
            hint: {
                type: String,
                required: true,
            },
            icon: {
                type: String,
                default: '',
            },
            validateErrors: {
                type: Array,
                required: true,
            },
        },
        computed: {
            prependIcon () {
                return this.icon || undefined;
            },
        },
    };
</script>
