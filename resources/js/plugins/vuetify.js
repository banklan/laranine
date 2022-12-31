import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

const opts = {
    theme:{
         themes: {
            light: {
                primary: '#99154E',
                primary_variant: '#812C67',
                secondary: '#c1e6ff',
                sec: '#00c9b7',
                light_text: '#3e3e3e',
                dark_text: '#2b2b2b',
                grey_text: '#19191a',
                info: '#62a8ff',
                icon_color: '#003864',
                warning_text: '#fb5d00'
            },
        },
    }
}

export default new Vuetify(opts)
