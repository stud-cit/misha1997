require('./bootstrap');

window.Vue = require("vue");

import router from "./router";

import 'vue-multiselect/dist/vue-multiselect.min.css';
import HeaderComponent from "./components/HeaderComponent";

// Vue.component("page-title", PageTitleComponent);

const app = new Vue({
    el: "#app",
    components: {
        HeaderComponent,
    },
    router
});
