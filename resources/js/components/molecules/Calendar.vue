<template>
    <FullCalendar
        ref="calendar"
        :options="calendarOptions"
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
                calendarOptions: {
                    plugins: [dayGridPlugin, interactionPlugin, momentTimezonePlugin],
                    locale: fullCalendarLocale[ locale ],
                    eventSources: [
                        {
                            events: this.getEventCallback,
                        },
                    ],
                    validRange: this.validRange,
                    defaultDate: this.defaultDate,
                    timeZone: this.timezone,
                    initialView: 'dayGridMonth',
                    height: 'auto',
                    dateClick: this.dateClick,
                },
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
                    [100, 200, 300, 500].forEach(ms => {
                        setTimeout(() => {
                            this.$refs.calendar.getApi().updateSize();
                        }, ms);
                    });
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

    @import '~@fullcalendar/common/main.css';
    @import '~@fullcalendar/daygrid/main.css';

    .fc {
        background-color: white;
        padding: 1em;
    }

</style>
