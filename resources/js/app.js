/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require("vue");


import router from "./router";


import HeaderComponent from "./components/HeaderComponent";
import FooterComponent from "./components/FooterComponent";






// Vue.component("page-title", PageTitleComponent);

const app = new Vue({
    el: "#app",
    components: {
        HeaderComponent,
        FooterComponent
    },
    router
});
