<template>
    <v-text-field
        :label="label"
        :value="value"
        :hint="hint"
        persistent-hint
        @input="val => $emit('input', val)"
        :error-messages="validateErrors"
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
            validateErrors: {
                type: Array,
                required: true,
            },
        },
    };
</script>
