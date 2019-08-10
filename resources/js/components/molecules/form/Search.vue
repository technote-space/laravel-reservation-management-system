<template>
    <div>
        {{ label }}
        {{ display }}
    </div>
</template>

<script>
    export default {
        props: {
            label: {
                type: String,
                required: true,
            },
            value: {
                type: [String, Number],
                required: true,
            },
            display: {
                type: String,
                required: true,
            },
            validateErrors: {
                type: Array,
                required: true,
            },
        },
        methods: {
            change (event) {
                console.log(event);
                //                this.$emit('change')
            },
        },
    };
</script>
