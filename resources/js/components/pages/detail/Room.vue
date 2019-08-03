<template>
    <Detail
        target-model="rooms"
        :detail-content-component="Room"
    />
</template>

<script>
    import Detail from '../../templates/Detail';
    import Room from '../../organisms/detail/Room';

    export default {
        data () {
            return {
                Room,
            };
        },
        metaInfo: {
            title: 'Room Detail',
        },
        components: {
            Detail,
        },
    };
</script>
