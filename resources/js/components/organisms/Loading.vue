<template>
    <v-overlay
        :value="isActiveOverlay"
        class="text-center"
        z-index="1000"
    >
        <v-progress-circular
            :color="progressColor"
            indeterminate
            size="64"
        />
        <div
            v-if="message"
            class="mt-3 font-weight-bold title"
        >
            {{ $t(`loading.${message}`) }}
        </div>
    </v-overlay>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default {
        computed: {
            ...mapGetters({
                isActiveOverlay: 'loading/isActiveOverlay',
                message: 'loading/getMessage',
                progressColor: 'loading/getProgressColor',
            }),
        },
    };
</script>
