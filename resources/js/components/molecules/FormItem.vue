<template>
    <div>
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
    import { get } from 'lodash';
    import TextForm from './form/Text';
    import DateForm from './form/Date';
    import SearchForm from './form/Search';

    export default {
        components: {
            TextForm,
            DateForm,
            SearchForm,
        },
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
                type: [String, Number],
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
            search () {
                return this.form.search || '';
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
