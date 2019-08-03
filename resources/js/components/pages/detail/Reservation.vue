<template>
    <Detail
        target-model="reservations"
        :detail-content-component="Reservation"
    />
</template>

<script>
    import Detail from '../../templates/Detail';
    import Reservation from '../../organisms/detail/Reservation';

    export default {
        data () {
            return {
                Reservation,
            };
        },
        metaInfo: {
            title: 'Reservation Detail',
        },
        components: {
            Detail,
        },
    };
</script>
