import Vue from "vue";
import Router from "vue-router";
import HomeComponent from "./components/HomeComponent";
import Error404 from './components/Error404';
Vue.use(Router);

export default new Router({
    mode: "history",
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeComponent
        },
        {
            path: '*',
            name: 'error',
            component: Error404,
        }

    ]
});
