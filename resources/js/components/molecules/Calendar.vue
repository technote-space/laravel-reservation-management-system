<template>
    <FullCalendar
        ref="calendar"
        :plugins="calendarPlugins"
        :locale="locale"
        :event-sources="eventSources"
        :valid-range="validRange"
        :default-date="defaultDate"
        :time-zone="timezone"
        default-view="dayGridMonth"
        height="parent"
        @dateClick="dateClick"
    />
</template>

<script>
    import { mapGetters } from 'vuex';
    import locale from '../../lang/detector';
    import fullCalendarLocale from '../../lang/fullCalendar';
    import FullCalendar from '@fullcalendar/vue';
    import dayGridPlugin from '@fullcalendar/daygrid';
    import interactionPlugin from '@fullcalendar/interaction';
    import momentTimezonePlugin from '@fullcalendar/moment-timezone';
    import moment from 'moment';

    export default {
        components: {
            FullCalendar,
        },
        props: {
            value: {
                type: String,
                default: () => '',
            },
            eventCallback: {
                type: Function,
                required: true,
            },
            dateClicked: {
                type: Function,
                default: () => () => {
                },
            },
            resetCalendarCallback: {
                type: Function,
                required: true,
            },
            validRange: {
                type: Object,
                default: () => {
                },
            },
            dialog: {
                type: Boolean,
                required: true,
            },
        },
        data () {
            return {
                calendarPlugins: [dayGridPlugin, interactionPlugin, momentTimezonePlugin],
                locale: fullCalendarLocale[ locale ],
                eventSources: [
                    {
                        events: this.getEventCallback,
                    },
                ],
            };
        },
        computed: {
            ...mapGetters({
                timezone: 'getTimezone',
            }),
            defaultDate () {
                return this.value || moment().format('YYYY-MM-DD');
            },
        },
        watch: {
            dialog: {
                handler: 'setup',
                immediate: true,
            },
        },
        methods: {
            getCalendar () {
                return this.$refs.calendar ? this.$refs.calendar.$options.calendar : null;
            },
            setup () {
                if (this.dialog) {
                    const calendar = this.getCalendar();
                    if (calendar) {
                        this.resetCalendar(calendar);
                        calendar.gotoDate(moment(this.defaultDate).toDate());
                    }
                }
            },
            getEventCallback ({ start, end }, callback) {
                this.eventCallback(moment(start), moment(end), callback, this.getCalendar());
            },
            dateClick (info) {
                this.dateClicked(info);
            },
            resetCalendar (calendar) {
                this.resetCalendarCallback(calendar);
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

        .fc-content {
            text-align: center;
        }
    }

</style>
