<template>
    <List
        target-model="guests"
        :list-content-component="Guest"
    />
</template>

<script>
    import List from '../../templates/List';
    import Guest from '../../organisms/list/Guest';

    export default {
        data () {
            return {
                Guest,
            };
        },
        metaInfo: {
            title: 'Guest List',
        },
        components: {
            List,
        },
    };
</script>
