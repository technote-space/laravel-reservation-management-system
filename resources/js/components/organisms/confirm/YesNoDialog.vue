<template>
    <v-dialog
        :value="dialog"
        persistent
        max-width="400px"
    >
        <v-card>
            <v-card-title>
                <span class="headline">{{ $t(title) }}</span>
            </v-card-title>

            <v-card-text>
                {{ $t(message) }}
            </v-card-text>

            <v-card-actions class="d-block text-center">
                <v-btn
                    color="primary"
                    dark
                    class="mb-2"
                    @click.stop="yes"
                >
                    {{ $t('misc.yes') }}
                </v-btn>
                <v-btn
                    color="primary"
                    dark
                    class="mb-2"
                    @click.stop="no"
                >
                    {{ $t('misc.no') }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        props: {
            dialog: {
                type: Boolean,
                required: true,
            },
            title: {
                type: String,
                default: 'misc.confirm',
            },
            message: {
                type: String,
                required: true,
            },
        },
        methods: {
            yes () {
                this.$emit('yes');
            },
            no () {
                this.$emit('no');
            },
        },
    };
</script>
