import 'bulma/css/bulma.min.css'
import './assets/css/bulma.config.css'
import './assets/js/bulma.config.js'
import 'boxicons/css/boxicons.min.css'

import { createApp } from 'vue'
import App from './App.vue'
import { vMaska } from "maska/vue"

createApp(App)
    .directive("maska", vMaska)
    .mount('#app')
