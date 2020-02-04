<template>
    <v-container
        fluid
        fill-height
    >
        <v-row
            justify="center"
        >
            {{ $t('pages.not_found') }}
        </v-row>
    </v-container>
</template>

<script>
    export default {
        metaInfo () {
            return {
                title: this.$t('pages.not_found'),
            };
        },
    };
</script>
