<template>
    <div>
        {{ item }}
    </div>
</template>

<script>
    export default {
        props: {
            item: {
                type: [String, Number],
                required: true,
            },
        },
    };
</script>
