import Vue from 'vue';
import Vuetify from 'vuetify/lib';

Vue.use(Vuetify);

import colors from "vuetify/lib/util/colors";

export default new Vuetify({
    theme: {
        themes: {
            light: {
                primary: colors.green.darken3,
                secondary: colors.yellow.darken1,
                accent: colors.shades.black,
                error: colors.green.accent4,
                creado: colors.teal.accent3,
                activo: colors.teal.darken1,
                enproceso: colors.yellow.darken3,
                cerrado: colors.green.darken2,
            },
            dark: {
                primary: colors.blue.lighten3,
            },
        },
    },
});
