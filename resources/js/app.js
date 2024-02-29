import './bootstrap.js';
import '@coreui/coreui/dist/js/coreui.bundle.min.js';
import { createApp } from 'vue'
import Pos from '../components/Pos.vue'
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

const app = createApp()
app.component('pos', Pos)
app.mount('#app')
