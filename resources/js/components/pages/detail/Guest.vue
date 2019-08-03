<template>
    <Detail
        target-model="guests"
        :detail-content-component="Guest"
    />
</template>

<script>
    import Detail from '../../templates/Detail';
    import Guest from '../../organisms/detail/Guest';

    export default {
        data () {
            return {
                Guest,
            };
        },
        metaInfo: {
            title: 'Guest Detail',
        },
        components: {
            Detail,
        },
    };
</script>
