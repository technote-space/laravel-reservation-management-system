<template>
    <List
        target-model="reservations"
    />
</template>

<script>
    import List from '../../templates/List';

    export default {
        metaInfo: {
            title: 'Reservation List',
        },
        components: {
            List,
        },
    };
</script>
