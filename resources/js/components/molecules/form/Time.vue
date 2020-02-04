<template>
    <v-dialog
        v-model="dialog"
        :disabled="isDisabled"
        content-class="time-picker-dialog"
        max-width="850px"
    >
        <template v-slot:activator="{ on }">
            <v-text-field
                :disabled="isDisabled"
                :label="label"
                :value="time"
                :prepend-icon="prependIcon"
                readonly
                :error-messages="validateErrors"
                v-on="on"
            />
        </template>
        <v-time-picker
            v-if="dialog"
            format="24hr"
            :dialog="dialog"
            :landscape="true"
            :scrollable="true"
            :value="time"
            @input="val => $emit('input', val)"
            @click:hour="hour => $emit('input', hour + ':' + minute)"
        />
    </v-dialog>
</template>

<script>
    import { mapGetters } from 'vuex';
    import moment from 'moment';

    export default {
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
            icon: {
                type: String,
                default: '',
            },
            validateErrors: {
                type: Array,
                required: true,
            },
        },
        data () {
            return {
                dialog: false,
                format: 'HH:mm',
            };
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
                return moment(moment().format('YYYY-MM-DD') + ' ' + this.formInputs[ 'reservations.checkout' ]).format(this.format);
            },
            minute () {
                return moment(moment().format('YYYY-MM-DD') + ' ' + this.formInputs[ 'reservations.checkout' ]).format('mm');
            },
            prependIcon () {
                return this.icon || 'timer';
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
