<template>
    <tr>
        <td
            v-for="item in items"
        >
            <component
                @edit-item="$emit('edit-item')"
                @delete-item="$emit('delete-item')"
                :is="item.componentIs"
                :item="item.item"
            />
        </td>
    </tr>
</template>

<script>
    import { get } from 'lodash';
    import ListRowValue from './ListRowValue';
    import ListRowComponent from './ListRowComponent';
    import ListRowAction from './ListRowAction';

    export default {
        components: {
            ListRowValue,
            ListRowComponent,
            ListRowAction,
        },
        computed: {
            items () {
                return this.headers.map(header => {
                    if ('action' === header.value) {
                        return {
                            componentIs: ListRowAction,
                            item: null,
                        };
                    }

                    const value = get(this.item, header.value, '');
                    if (typeof value === 'object') {
                        return {
                            componentIs: ListRowComponent,
                            item: value,
                        };
                    }

                    return {
                        componentIs: ListRowValue,
                        item: value,
                    };
                });
            },
        },
        props: {
            item: {
                type: Object,
                required: true,
            },
            headers: {
                type: Array,
                required: true,
            },
        },
    };
</script>
