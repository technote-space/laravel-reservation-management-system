<template>
    <v-dialog
        :disabled="isDisabled"
        v-model="dialog"
        max-width="850px"
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
            v-if="dialog"
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
            roomId () {
                return this.formInputs[ 'reservations.room_id' ];
            },
            isDisabled () {
                return !this.roomId;
            },
            isValidTargetEvent () {
                return this.value;
            },
            targetDate () {
                return this.value;
            },
            isValidActiveEvent () {
                return this.formInputs[ 'reservations.start_date' ] && this.formInputs[ 'reservations.end_date' ];
            },
            activeEventStart () {
                return moment(this.formInputs[ 'reservations.start_date' ]).format(this.format);
            },
            activeEventEnd () {
                return moment(this.formInputs[ 'reservations.end_date' ]).add(1, 'days').format(this.format);
            },
        },
        methods: {
            ...mapActions({
                search: 'crud/search',
            }),
            async eventCallback (start, end, callback, calendar) {
                const reserves = await this.search({
                    model: this.model,
                    query: {
                        'start_date': start.format(this.format),
                        'end_date': end.format(this.format),
                        'room_id': this.roomId,
                    },
                });

                if (calendar) {
                    this.clearAllEvents(calendar);
                }
                const events = reserves.filter(reserve => reserve.id !== this.detail.id).map(this.createEvent);
                if (this.isValidTargetEvent) {
                    events.push(this.createTargetEvent());
                }
                if (this.isValidActiveEvent) {
                    events.push(this.createActiveEvent());
                }
                callback(events);
            },
            createEvent (reserve) {
                const start = moment(reserve.start_datetime);
                const end = moment(reserve.end_datetime);
                return {
                    start: start.format(this.format),
                    end: end.format(this.format),
                    allDay: true,
                    color: '#a99',
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
            dateClicked (info) {
                this.$emit('input', info.dateStr);
                this.dialog = false;
            },
            resetCalendarCallback (calendar) {
                this.clearAllEvents(calendar);
                if (this.isValidTargetEvent) {
                    calendar.addEvent(this.createTargetEvent());
                }
                if (this.isValidActiveEvent) {
                    calendar.addEvent(this.createActiveEvent());
                }
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
