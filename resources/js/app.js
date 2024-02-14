import './bootstrap';

import { createApp } from 'vue'

import App from './App.vue';
import router from './router';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import Toasted from "@hoppscotch/vue-toasted"
import "@hoppscotch/vue-toasted/style.css";

const app = createApp(App);
app.use(router);

app.use(VueSweetalert2);
window.Swal = app.config.globalProperties.$swal;

app.use(Toasted);
window.toast = app.config.globalProperties.$toasted;

app.mount("#app")
