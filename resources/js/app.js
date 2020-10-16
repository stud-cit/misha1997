require('./bootstrap');

window.Vue = require("vue");

import router from "./router";
import store from "./store";

import Paginate from 'vuejs-paginate'
Vue.component('paginate', Paginate);
import 'vue-multiselect/dist/vue-multiselect.min.css';
import HeaderComponent from "./components/HeaderComponent";
import Vuelidate from 'vuelidate';
Vue.use(Vuelidate);
// Vue.component("page-title", PageTitleComponent);

Vue.prototype.$http = axios;

const token = localStorage.getItem('key');
if (token) {
    Vue.prototype.$http.defaults.headers.common['Authorization'] = token;
}

const app = new Vue({
    el: "#app",
    components: {
        HeaderComponent,
    },
    store,
    router
});
