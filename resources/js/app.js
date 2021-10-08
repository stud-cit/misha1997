require('./bootstrap');

import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import Vuelidate from 'vuelidate'

import router from "./routes"
import store from "./store"

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'vue-multiselect/dist/vue-multiselect.min.css'

import AppComponent from './views/App'

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(Vuelidate)

Vue.prototype.$http = axios

Vue.config.productionTip = false

const app = new Vue({
    components: {
      AppComponent
    },
    store,
    router
}).$mount('#app');