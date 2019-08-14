<template>
    <v-overlay
        :value="isActiveOverlay"
        class="text-center"
    >
        <v-progress-circular
            indeterminate
            size="64"
            :color="progressColor"
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
