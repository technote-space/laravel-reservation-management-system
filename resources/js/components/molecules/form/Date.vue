<template>
    <v-dialog
        :disabled="isDisabled"
        v-model="dialog"
        full-width
    >
        <template v-slot:activator="{ on }">
            <v-text-field
                :disabled="isDisabled"
                :label="label"
                :value="value"
                prepend-icon="event"
                readonly
                v-on="on"
                :error-messages="validateErrors"
            />
        </template>
        <Calendar
            ref="calendar"
            :value="value"
            :event-callback="eventCallback"
            :date-clicked="dateClicked"
            :reset-calendar-callback="resetCalendarCallback"
            :valid-range="validRange"
            :dialog="dialog"
        />
    </v-dialog>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import moment from 'moment';
    import Calendar from '../Calendar';

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
            detail: {
                type: Object,
                required: true,
            },
            formInputs: {
                type: Object,
                required: true,
            },
            form: {
                type: Object,
                required: true,
            },
            label: {
                type: String,
                required: true,
            },
            value: {
                type: String,
                required: true,
            },
            validateErrors: {
                type: Array,
                required: true,
            },
        },
        computed: {
            ...mapGetters({
                model: 'crud/getTargetModel',
            }),
            validRange () {
                return {
                    start: this.min,
                    end: this.max,
                };
            },
            min () {
                if ('reservations.end_date' === this.form.name) {
                    return this.formInputs[ 'reservations.start_date' ];
                }
                return moment().subtract(12, 'months').startOf('month').format(this.format);
            },
            max () {
                if ('reservations.start_date' === this.form.name) {
                    return moment(this.formInputs[ 'reservations.end_date' ]).add(1, 'days').format(this.format);
                }
                return moment().add(24, 'months').endOf('month').format(this.format);
            },
            isDisabled () {
                return !this.formInputs[ 'reservations.room_id' ];
            },
            targetDate () {
                return this.value || moment().format(this.format);
            },
            activeEventStart () {
                return (this.formInputs[ 'reservations.start_date' ] ? moment(this.formInputs[ 'reservations.start_date' ]) : moment()).format(this.format);
            },
            activeEventEnd () {
                return (this.formInputs[ 'reservations.end_date' ] ? moment(this.formInputs[ 'reservations.end_date' ]) : moment()).add(1, 'days').format(this.format);
            },
        },
        methods: {
            ...mapActions({
                search: 'crud/search',
                check: 'crud/checkReservation',
            }),
            async eventCallback (start, end, callback, calendar) {
                const reserves = await this.search({
                    model: this.model,
                    query: {
                        'start_date': start.format(this.format),
                        'end_date': end.format(this.format),
                    },
                });

                if (calendar) {
                    this.clearAllEvents(calendar);
                }
                callback(reserves.filter(reserve => reserve.id !== this.detail.id).map(this.createEvent).concat([this.createTargetEvent(), this.createActiveEvent()]));
            },
            createEvent (reserve) {
                const start = moment(reserve.start_datetime);
                const end = moment(reserve.end_datetime);
                return {
                    start: start.format(this.format),
                    end: end.format(this.format),
                    allDay: true,
                    color: '#ec6',
                    textColor: 'black',
                    rendering: 'background',
                };
            },
            createTargetEvent () {
                return {
                    id: 'targetEvent',
                    title: this.$t('column.' + this.form.text),
                    start: this.targetDate,
                    allDay: true,
                    color: '#0a6',
                    textColor: '#fff',
                    displayEventTime: false,
                    startEditable: false,
                    durationEditable: false,
                    resourceEditable: false,
                };
            },
            createActiveEvent () {
                return {
                    id: 'activeEvent',
                    start: this.activeEventStart,
                    end: this.activeEventEnd,
                    allDay: true,
                    color: '#0ec',
                    textColor: 'black',
                    rendering: 'background',
                };
            },
            async dateClicked (info) {
                //                const data = {
                //                    reservationId: this.detail.id,
                //                    roomId: this.formInputs[ 'reservations.room_id' ],
                //                    guestId: this.formInputs[ 'reservations.guest_id' ],
                //                    startDate: this.formInputs[ 'reservations.start_date' ],
                //                    endDate: this.formInputs[ 'reservations.end_date' ],
                //                };
                //                data[ this.form.checkKey ] = info.dateStr;
                //
                //                if (!await this.check(data)) {
                //                    return;
                //                }

                this.$emit('input', info.dateStr);
                this.dialog = false;
            },
            resetCalendarCallback (calendar) {
                this.clearAllEvents(calendar);
                calendar.addEvent(this.createTargetEvent());
                calendar.addEvent(this.createActiveEvent());
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

<style lang='scss'>

    @import '~@fullcalendar/core/main.css';
    @import '~@fullcalendar/daygrid/main.css';

    .fc {
        background-color: white;
        padding: 1em;
    }

</style>
