import Vue from 'vue';
import Vuetify from 'vuetify';
import colors from 'vuetify/lib/util/colors';
import 'vuetify/dist/vuetify.min.css';
import '@mdi/font/css/materialdesignicons.css';
import 'material-design-icons-iconfont/dist/material-design-icons.css';

Vue.use(Vuetify);

export default new Vuetify({
    theme: {
        themes: {
            light: {
                primary: colors.teal.base,
                secondary: colors.teal.lighten1,
                accent: colors.lightBlue.base,
                error: colors.red.base,
                warning: colors.orange.base,
                info: colors.purple.base,
                success: colors.lightGreen.base,
            },
        },
    },
});
