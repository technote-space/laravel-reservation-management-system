<template>
    <List
        target-model="reservations"
        :list-content-component="Reservation"
    />
</template>

<script>
    import List from '../../templates/List';
    import Reservation from '../../organisms/list/Reservation';

    export default {
        data () {
            return {
                Reservation,
            };
        },
        metaInfo: {
            title: 'Reservation List',
        },
        components: {
            List,
        },
    };
</script>
