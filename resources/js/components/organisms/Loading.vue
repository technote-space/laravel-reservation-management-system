<template>
    <v-layout
        row
        justify-center
    >
        <v-dialog
            :value="isActiveOverlay"
            persistent
            content
            content-class="centered-dialog"
        >
            <v-container fill-height>
                <v-layout
                    column
                    justify-center
                    align-center
                >
                    <v-progress-circular
                        indeterminate
                        :size="70"
                        :width="7"
                        :color="progressColor"
                    />
                    <h1 v-if="message != null">
                        {{ message }}
                    </h1>
                </v-layout>
            </v-container>
        </v-dialog>
    </v-layout>
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

<style>
    .v-dialog.centered-dialog {
        background: #282c2dad;
        box-shadow: none;
        border-radius: 6px;
        width: auto;
        color: whitesmoke;
    }
</style>
