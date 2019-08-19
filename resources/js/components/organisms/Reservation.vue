<template>
    <v-dialog
        v-model="dialog"
        max-width="850px"
    >
        <template v-slot:activator="{ on: { click } }">
            <v-btn
                icon
                v-on:click="click"
            >
                <v-icon>mdi-calendar-month</v-icon>
            </v-btn>
        </template>
        <Calendar
            v-if="dialog"
            ref="calendar"
            :event-callback="eventCallback"
            :reset-calendar-callback="resetCalendarCallback"
            :dialog="dialog"
        />
    </v-dialog>
</template>

<script>
    import { mapActions } from 'vuex';
    import moment from 'moment';
    import Calendar from '../molecules/Calendar';

    export default {
        components: {
            Calendar,
        },
        data () {
            return {
                dialog: false,
                format: 'YYYY-MM-DD',
            };
        },
        props: {
            roomId: {
                type: Number,
                required: true,
            },
        },
        methods: {
            ...mapActions({
                search: 'crud/search',
            }),
            async eventCallback (start, end, callback, calendar) {
                const reserves = await this.search({
                    model: 'reservations',
                    query: {
                        'start_date': start.format(this.format),
                        'end_date': end.format(this.format),
                        'room_id': this.roomId,
                    },
                });

                if (calendar) {
                    this.clearAllEvents(calendar);
                }
                callback(reserves.map(this.createEvent));
            },
            createEvent (reserve) {
                const start = moment(reserve.start_datetime);
                const end = moment(reserve.end_datetime);
                return {
                    title: this.getReservationTitle(reserve),
                    start: start.format(this.format),
                    end: end.format(this.format),
                    allDay: true,
                    color: '#0a6',
                    textColor: '#fff',
                    displayEventTime: false,
                    startEditable: false,
                    durationEditable: false,
                    resourceEditable: false,
                };
            },
            getReservationTitle (reserve) {
                return reserve.guest.detail.name;
            },
            resetCalendarCallback (calendar) {
                this.clearAllEvents(calendar);
            },
            clearAllEvents (calendar) {
                this.clearEvent('targetEvent', calendar);
                this.clearEvent('activeEvent', calendar);
            },
            clearEvent (name, calendar) {
                const event = calendar.getEventById(name);
                if (event) {
                    event.remove();
                    this.clearEvent(name, calendar);
                }
            },
        },
    };
</script>
