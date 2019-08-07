<template>
    <v-container
        fluid
        fill-height
    >
        <v-layout
            wrap
            justify-center
        >
            {{ $t('pages.dashboard') }}
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        metaInfo () {
            return {
                title: this.$t('pages.dashboard'),
            };
        },
    };
</script>
