<template>
    <div>
        <!-- eslint-disable-next-line vue/require-component-is -->
        <component
            :is="componentIs"
            :form="form"
            :detail="detail"
            :form-inputs="formInputs"
            :value="value"
            :label="label"
            :hint="hint"
            :validate-errors="validateErrors"
            @input="val => $emit('input', val)"
        />
    </div>
</template>

<script>
    import TextForm from './form/Text';
    import DateForm from './form/Date';
    import SearchForm from './form/Search';

    export default {
        props: {
            detail: {
                type: Object,
                required: true,
            },
            formInputs: {
                type: Object,
                required: true,
            },
            form: {
                type: Object,
                required: true,
            },
            value: {
                validator: prop => null === prop || 'string' === typeof prop || 'number' === typeof prop,
                required: true,
            },
            validateErrors: {
                type: Array,
                required: true,
            },
            increment: {
                type: Number,
                required: true,
            },
        },
        computed: {
            name () {
                return this.form.text;
            },
            label () {
                return this.$t('column.' + this.name);
            },
            type () {
                return this.form.type || 'text';
            },
            hint () {
                return this.form.hint ? this.$t(this.form.hint) : '';
            },
            componentIs () {
                if ('date' === this.type) {
                    return DateForm;
                }
                if ('search' === this.type) {
                    return SearchForm;
                }
                return TextForm;
            },
        },
    };
</script>
