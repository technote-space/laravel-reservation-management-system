<template>
    <tr>
        <td
            v-for="component in items"
            :key="component.index"
        >
            <component
                :is="component.componentIs"
                :item="component.item"
                @edit-item="$emit('edit-item')"
                @delete-item="$emit('delete-item')"
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
        computed: {
            items () {
                return this.headers.map((header, index) => {
                    if ('action' === header.value) {
                        return {
                            componentIs: ListRowAction,
                            item: null,
                            index,
                        };
                    }

                    const value = get(this.item, header.value, '');
                    if ('object' === typeof value) {
                        return {
                            componentIs: ListRowComponent,
                            item: value,
                            index,
                        };
                    }

                    return {
                        componentIs: ListRowValue,
                        item: value,
                        index,
                    };
                });
            },
        },
    };
</script>
