<template>
    <v-container
        fluid
        fill-height
    >
        <v-layout
            align-center
            justify-center
        >
            <Sales />
        </v-layout>
    </v-container>
</template>

<script>
    import Sales from '../organisms/Sales';

    export default {
        metaInfo () {
            return {
                title: this.$t('pages.dashboard'),
            };
        },
        components: {
            Sales,
        },
    };
</script>
