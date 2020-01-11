<script>
    export default {
        props: {
            item: {
                type: [Object],
                required: true,
            },
        },
        render () {
            return this.item;
        },
    };
</script>
