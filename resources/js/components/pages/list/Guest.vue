<template>
    <List
        target-model="guests"
    />
</template>

<script>
    import List from '../../templates/List';

    export default {
        metaInfo: {
            title: 'Guest List',
        },
        components: {
            List,
        },
    };
</script>
