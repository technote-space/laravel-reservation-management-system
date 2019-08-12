<template>
    <v-card
        raised
        class="pa-6"
    >
        <v-card-title>
            {{ $t('misc.sales') }}
        </v-card-title>
        <v-card-text>
            <Bar
                :label="label"
                :dataset="data"
            />
        </v-card-text>
    </v-card>
</template>

<script>
    import moment from 'moment';
    import Bar from '../molecules/Bar';
    import { apiGet } from '../../utils/api';
    import { arrayToObject } from '../../utils/misc';

    export default {
        components: {
            Bar,
        },
        data () {
            return {
                data: [],
                label: this.$t('misc.monthly_sales'),
                start: moment().subtract(11, 'months').startOf('month').format('YYYY-MM-DD'),
                end: moment().add(1, 'months').startOf('month').format('YYYY-MM-DD'),
            };
        },
        async mounted () {
            const { response } = await apiGet('summary', {
                data: {
                    'start_date': this.start,
                    'end_date': this.end,
                },
            });
            if (response) {
                this.data = Object.values(arrayToObject(Object.keys(response.data), {
                    getItem: key => ({ label: key, value: response.data[ key ] }),
                }));
            }
        },
    };
</script>
