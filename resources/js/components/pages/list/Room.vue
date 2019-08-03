<template>
    <List
        target-model="rooms"
        :list-content-component="Room"
    />
</template>

<script>
    import List from '../../templates/List';
    import Room from '../../organisms/list/Room';

    export default {
        data () {
            return {
                Room,
            };
        },
        metaInfo: {
            title: 'Room List',
        },
        components: {
            List,
        },
    };
</script>
