<template>
    <v-card
        raised
        class="pa-6 ma-4"
    >
        <v-card-title>
            {{ $t('misc.checkin') }}
        </v-card-title>
        <v-card-text>
            <v-data-table
                :headers="tableHeaders"
                :items="items"
                hide-default-footer
                disable-sort
                class="elevation-1 pa-5 mb-3"
            >
                <template v-slot:top>
                    <YesCancel
                        :dialog="dialog"
                        message="messages.cancel_reservation"
                        @yes="cancel"
                        @cancel="close"
                    />
                </template>
                <template v-slot:item.action_checkin="{ item }">
                    <v-btn
                        v-if="isEnabledCheckin(item)"
                        color="primary"
                        @click="checkin(item)"
                    >
                        <v-icon
                            small
                        >
                            home
                        </v-icon>
                        {{ $t('misc.checkin') }}
                    </v-btn>
                    <v-btn
                        v-else-if="isCheckin(item)"
                        :disabled="true"
                    >
                        {{ $t('messages.checked_in') }}
                    </v-btn>
                    <v-btn
                        v-else-if="isCheckout(item)"
                        :disabled="true"
                    >
                        {{ $t('messages.checked_out') }}
                    </v-btn>
                </template>
                <template v-slot:item.action_cancel="{ item }">
                    <v-btn
                        v-if="isEnabledCancel(item)"
                        color="error"
                        @click="cancelConfirm(item)"
                    >
                        <v-icon
                            small
                        >
                            cancel
                        </v-icon>
                        {{ $t('misc.cancel') }}
                    </v-btn>
                    <v-btn
                        v-else-if="isCanceled(item)"
                        :disabled="true"
                    >
                        {{ $t('messages.canceled') }}
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
    import YesCancel from '../organisms/confirm/YesCancelDialog';

    export default {
        components: {
            YesCancel,
        },
        props: {
            date: {
                type: String,
                required: true,
            },
        },
        data () {
            return {
                targetId: null,
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
                    { text: this.$t('column.phone'), value: 'detail.guest_phone' },
                    { text: this.$t('column.room_name'), value: 'room.name' },
                    { text: this.$t('column.days'), value: 'stays' },
                    { text: this.$t('misc.checkin'), value: 'action_checkin', align: 'center' },
                    { text: this.$t('misc.cancel'), value: 'action_cancel', align: 'center' },
                ];
            },
            dialog () {
                return null !== this.targetId;
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
                const { response } = await apiGet('checkin', {
                    data: {
                        'date': this.date,
                    },
                });
                this.items = response.data;
            },
            async checkin (item) {
                await apiPatch('checkin', {
                    data: {
                        id: item.id,
                    },
                });
                await this.setup();
            },
            cancelConfirm (item) {
                this.targetId = item.id;
            },
            async cancel () {
                await apiPatch('cancel', {
                    data: {
                        id: this.targetId,
                    },
                });
                this.close();
                await this.setup();
            },
            close () {
                this.targetId = null;
            },
            isReserved (item) {
                return 'reserved' === item.status;
            },
            isCanceled (item) {
                return 'canceled' === item.status;
            },
            isCheckin (item) {
                return 'checkin' === item.status;
            },
            isCheckout (item) {
                return 'checkout' === item.status;
            },
            isEnabledCancel (item) {
                return this.isReserved(item) || this.isCheckin(item);
            },
            isEnabledCheckin (item) {
                return this.isReserved(item);
            },
        },
    };
</script>
