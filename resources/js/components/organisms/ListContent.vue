<template>
    <v-hover>
        <v-card
            slot-scope="{ hover }"
            :class="`elevation-${hover ? 15 : 1}`"
            height="100%"
            class="crud-card pa-1 ma-2"
            max-width="100%"
            @click.stop="showDetail(item)"
        >
            <component
                :is="listContentComponent"
                :item="item"
            />
        </v-card>
    </v-hover>
</template>

<script>
    import { getModelDetailRouter } from '../../store/modules/crud/utils';

    export default {
        props: [
            'item',
            'targetModel',
            'listContentComponent',
        ],
        methods: {
            showDetail: function (item) {
                this.$router.push(getModelDetailRouter(this.targetModel, item.id));
            },
        },
    };
</script>
