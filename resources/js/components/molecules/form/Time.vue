<template>
    <v-dialog
        :disabled="isDisabled"
        v-model="dialog"
        contentClass="time-picker-dialog"
        max-width="850px"
    >
        <template v-slot:activator="{ on }">
            <v-text-field
                :disabled="isDisabled"
                :label="label"
                :value="time"
                prepend-icon="timer"
                readonly
                v-on="on"
                :error-messages="validateErrors"
            />
        </template>
        <v-time-picker
            :landscape="true"
            v-if="dialog"
            :value="time"
            :dialog="dialog"
            @input="val => $emit('input', val)"
            @click:hour="hour => $emit('input', hour + ':' + minute)"
        />
    </v-dialog>
</template>

<script>
    import { mapGetters } from 'vuex';
    import moment from 'moment';

    export default {
        data () {
            return {
                dialog: false,
                format: 'hh:mm',
            };
        },
        props: {
            formInputs: {
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
            roomId () {
                return this.formInputs[ 'reservations.room_id' ];
            },
            isDisabled () {
                return !this.roomId;
            },
            time () {
                return moment(moment().format('YYYY-MM-DD') + ' ' + this.formInputs[ 'reservations.check_out' ]).format(this.format);
            },
            minute () {
                return moment(moment().format('YYYY-MM-DD') + ' ' + this.formInputs[ 'reservations.check_out' ]).format('mm');
            },
        },
        methods: {
            dateClicked (info) {
                this.$emit('input', info.dateStr);
                this.dialog = false;
            },
        },
    };
</script>

<style lang='scss'>
    .v-dialog.time-picker-dialog {
        width: auto;
    }
</style>
