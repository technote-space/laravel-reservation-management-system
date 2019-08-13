<script>
    import { Bar } from 'vue-chartjs';

    export default {
        extends: Bar,
        props: {
            title: {
                type: String,
                default: () => null,
            },
            label: {
                type: String,
                required: true,
            },
            backgroundColor: {
                type: String,
                default: () => 'rgba(54, 162, 235, 0.2)',
            },
            borderColor: {
                type: String,
                default: () => 'rgb(54, 162, 235)',
            },
            borderWidth: {
                type: Number,
                default: () => 1,
            },
            dataKey: {
                type: String,
                default: () => 'value',
            },
            labelKey: {
                type: String,
                default: () => 'label',
            },
            dataset: {
                type: Array,
                required: true,
            },
        },
        computed: {
            data () {
                return this.dataset.map(data => data[ this.dataKey ]);
            },
            labels () {
                return this.dataset.map(data => data[ this.labelKey ]);
            },
            chartData () {
                return {
                    labels: this.labels,
                    datasets: [
                        {
                            label: this.label,
                            backgroundColor: this.backgroundColor,
                            borderColor: this.borderColor,
                            borderWidth: this.borderWidth,
                            data: this.data,
                        },
                    ],
                };
            },
            options () {
                return {
                    title: {
                        display: null !== this.title,
                        text: this.title,
                    },
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                },
                            },
                        ],
                    },
                };
            },
        },
        watch: {
            dataset () {
                this.renderChart(this.chartData, this.options);
            },
        },
        mounted: function () {
            this.renderChart(this.chartData, this.options);
        },
    };
</script>
