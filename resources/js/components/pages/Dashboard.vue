<template>
    <v-container
        fluid
        fill-height
    >
        <v-row
            align="center"
        >
            <v-col
                justify="center"
                sm="12"
                class="pb-0"
            >
                <v-dialog
                    v-model="selectDate"
                    max-width="850px"
                >
                    <template v-slot:activator="{ on: { click } }">
                        <v-text-field
                            :disabled="isLoading"
                            :label="$t('misc.date')"
                            :value="date"
                            class="d-inline-block float-right"
                            @click="click"
                        />
                    </template>
                    <Calendar
                        v-if="selectDate"
                        :event-callback="eventCallback"
                        :date-clicked="dateClicked"
                        :reset-calendar-callback="resetCalendarCallback"
                        :dialog="selectDate"
                    />
                </v-dialog>
            </v-col>
        </v-row>
        <v-row>
            <v-col
                sm="12"
                class="pt-0"
            >
                <Checkin
                    :date="date"
                />
            </v-col>
        </v-row>
        <v-row>
            <v-col
                sm="12"
                class="pt-0"
            >
                <Checkout
                    :date="date"
                />
            </v-col>
        </v-row>
        <v-row
            class="ma-3"
        >
            <v-divider />
        </v-row>
        <v-row
            align="center"
        >
            <v-col
                justify="center"
                sm="12"
                class="pb-0"
            >
                <v-select
                    v-model="roomId"
                    :disabled="isLoading"
                    :items="rooms"
                    class="d-inline-block float-right"
                />
            </v-col>
        </v-row>
        <v-row>
            <v-col
                v-for="setting in settings"
                :key="setting.label"
                sm="12"
                md="6"
                class="pt-0"
            >
                <Sales
                    :label="setting.label"
                    :start="setting.start"
                    :end="setting.end"
                    :type="setting.type"
                    :room-id="roomId"
                />
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import moment from 'moment';
    import Sales from '../organisms/Sales';
    import Checkin from '../organisms/Checkin';
    import Checkout from '../organisms/Checkout';
    import Calendar from '../molecules/Calendar';

    export default {
        components: {
            Sales,
            Checkin,
            Checkout,
            Calendar,
        },
        metaInfo () {
            return {
                title: this.$t('pages.dashboard'),
            };
        },
        data () {
            return {
                rooms: [],
                roomId: '',
                date: '',
                selectDate: false,
                settings: [
                    {
                        label: this.$t('misc.monthly_sales'),
                        start: moment().subtract(11, 'months').startOf('month').format('YYYY-MM-DD'),
                        end: moment().add(1, 'months').startOf('month').format('YYYY-MM-DD'),
                        type: 'monthly',
                    },
                    {
                        label: this.$t('misc.daily_sales'),
                        start: moment().startOf('month').format('YYYY-MM-DD'),
                        end: moment().add(1, 'months').startOf('month').format('YYYY-MM-DD'),
                        type: 'daily',
                    },
                ],
            };
        },
        computed: {
            ...mapGetters({
                isLoading: 'loading/isLoading',
            }),
        },
        async mounted () {
            this.rooms = [
                {
                    text: this.$t('misc.all_rooms'),
                    value: '',
                },
            ].concat((await this.search({
                model: 'rooms',
            })).map(item => ({
                text: item.name,
                value: item.id,
            })));
            this.roomId = '';
        },
        methods: {
            ...mapActions({
                search: 'crud/search',
            }),
            eventCallback () {
            },
            resetCalendarCallback () {
            },
            dateClicked (info) {
                this.date = info.dateStr;
                this.selectDate = false;
            },
        },
    };
</script>
