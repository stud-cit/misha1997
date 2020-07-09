/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require("vue");

import router from "./router";

import 'vue-multiselect/dist/vue-multiselect.min.css';
import HeaderComponent from "./components/HeaderComponent";
import Step2 from './components/Publications/Step2';






// Vue.component("page-title", PageTitleComponent);

const app = new Vue({
    el: "#app",
    components: {
        HeaderComponent,
        Step2
    },
    router
});
