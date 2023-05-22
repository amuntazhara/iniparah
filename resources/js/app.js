import './bootstrap';

// css
import 'bootstrap/dist/css/bootstrap.min.css'
import '../../public/storage/css/styles.min.css'
import '../css/app.css'

// js
import '../../public/storage/libs/jquery/dist/jquery.js'
import '../../public/storage/libs/simplebar/dist/simplebar.js'
import 'bootstrap'
import '../../public/storage/js/sidebarmenu.js'
import '../../public/storage/js/app.min.js'

import { createApp } from 'vue';
import App from '@/vue/Main.vue'
createApp(App).mount('#app')