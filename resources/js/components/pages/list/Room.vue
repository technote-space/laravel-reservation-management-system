<template>
    <List
        target-model="rooms"
    />
</template>

<script>
    import List from '../../templates/List';

    export default {
        metaInfo: {
            title: 'Room List',
        },
        components: {
            List,
        },
    };
</script>
