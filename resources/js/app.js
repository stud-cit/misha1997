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
