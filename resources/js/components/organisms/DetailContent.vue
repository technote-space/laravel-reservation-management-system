<template>
    <v-card
        v-if="item"
        height="100%"
        max-width="100%"
    >
        <component
            :is="detailContentComponent"
            :item="item"
        />
    </v-card>
</template>

<script>
    export default {
        props: [
            'item',
            'targetModel',
            'detailContentComponent',
        ],
    };
</script>
