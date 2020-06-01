import Vue from "vue";
import Router from "vue-router";
import Home from "./components/Home";
import Auth from "./components/Auth";
import Profile from "./components/Profile";
import Publications from "./components/Publications/Index";
import PublicationsAdd from "./components/Publications/Add";
import Notifications from "./components/Notifications";
import Users from "./components/Users";
import Error404 from './components/Error404';
Vue.use(Router);

export default new Router({
    mode: "history",
    routes: [
        {
            path: '/',
            name: 'auth',
            component: Auth
        },
        {
            path: '/home',
            name: 'home',
            component: Home
        },
        {
            path: '/profile',
            name: 'profile',
            component: Profile
        },
        {
            path: '/publications',
            name: 'publications',
            component: Publications,

        },
        {
            path: '/publications/add',
            name: 'publications-add',
            component: PublicationsAdd
        },
        {
            path: '/publications/:id',
            component: PublicationsAdd
        },
        {
            path: '/notifications',
            name: 'notifications',
            component: Notifications
        },
        {
            path: '/users',
            name: 'users',
            component: Users
        },
        {
            path: '*',
            name: 'error',
            component: Error404,
        }

    ]
});
