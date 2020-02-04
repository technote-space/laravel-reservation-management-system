<template>
    <v-card
        raised
        class="pa-6 ma-4"
    >
        <v-card-title>
            {{ $t('misc.checkout') }}
        </v-card-title>
        <v-card-text>
            <v-data-table
                :headers="tableHeaders"
                :items="items"
                hide-default-footer
                disable-sort
                class="elevation-1 pa-5 mb-3"
            >
                <template v-slot:item.action_checkout="{ item }">
                    <v-btn
                        v-if="isEnabledCheckout(item)"
                        color="primary"
                        @click="checkout(item)"
                    >
                        <v-icon
                            small
                        >
                            home
                        </v-icon>
                        {{ $t('misc.checkout') }}
                    </v-btn>
                    <v-btn
                        v-else-if="isCheckout(item)"
                        :disabled="true"
                    >
                        {{ $t('messages.checked_out') }}
                    </v-btn>
                    <v-btn
                        v-else
                        :disabled="true"
                    >
                        {{ $t('messages.not_checked_in') }}
                    </v-btn>
                </template>
            </v-data-table>
            <v-overlay
                :value="isLoading"
                absolute
                class="text-center"
            >
                <v-progress-circular
                    indeterminate
                    size="32"
                />
            </v-overlay>
        </v-card-text>
    </v-card>
</template>

<script>
    import { mapGetters } from 'vuex';
    import { apiGet, apiPatch } from '../../utils/api';

    export default {
        props: {
            date: {
                type: String,
                required: true,
            },
        },
        data () {
            return {
                items: [],
            };
        },
        computed: {
            ...mapGetters({
                isLoading: 'loading/isLoading',
            }),
            tableHeaders () {
                return [
                    { text: this.$t('column.name'), value: 'detail.guest_name' },
                    { text: this.$t('column.katakana'), value: 'detail.guest_name_kana' },
                    { text: this.$t('column.room_name'), value: 'room.name' },
                    { text: this.$t('column.days'), value: 'stays' },
                    { text: this.$t('column.checkout_time'), value: 'checkout' },
                    { text: this.$t('misc.checkout'), value: 'action_checkout', align: 'center' },
                ];
            },
        },
        watch: {
            date: {
                handler: 'setup',
                immediate: true,
            },
        },
        methods: {
            async setup () {
                const { response } = await apiGet('checkout', {
                    data: {
                        'date': this.date,
                    },
                });
                if (response) {
                    this.items = response.data;
                }
            },
            async checkout (item) {
                await apiPatch('checkout', {
                    data: {
                        id: item.id,
                    },
                });
                await this.setup();
            },
            isCheckout (item) {
                return 'checkout' === item.status;
            },
            isCheckin (item) {
                return 'checkin' === item.status;
            },
            isEnabledCheckout (item) {
                return this.isCheckin(item);
            },
        },
    };
</script>
