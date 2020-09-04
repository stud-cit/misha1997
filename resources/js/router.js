import Vue from "vue";
import Router from "vue-router";
import Home from "./components/Home";
import Auth from "./components/Auth";
import Profile from "./components/Profile";
import Publications from "./components/Publications/Index";
import PublicationsAdd from "./components/Publications/Add";
import PublicationsView from "./components/Publications/View";
import Notifications from "./components/Notifications";
import Users from "./components/Users";
import Register from "./components/Register";
import Error404 from './components/Error404';

import store from './store/user';

Vue.use(Router);

let router = new Router({
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
            component: Home,
            meta: {
                requiresRegister: false
            }
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
            meta: {
                requiresAuth: false
            }
        },
        {
            path: '/profile/:id',
            name: 'profile',
            component: Profile,
            meta: {
                requiresRegister: false
            }
        },
        {
            path: '/publications',
            name: 'publications',
            component: Publications,
            meta: {
                requiresRegister: false
            }
        },
        {
            path: '/publications/add',
            name: 'publications-add',
            component: PublicationsAdd
        },
        {
            path: '/publications/:id',
            component: PublicationsView
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

router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.requiresAuth)) {
        axios.get('/api/check-login')
        .then((response) => {
            if(response.data == 'ok') {
                next();
            } else {
                next({
                    path: '/',
                    params: { nextUrl: to.fullPath }
                });
            }
        })
    } else if (to.matched.some(record => record.meta.requiresRegister)) {
        axios.get('/api/check-register')
        .then((response) => {
            if(response.data.status == 'ok') {
                localStorage.setItem('userId', response.data.userId);
                next();
            } else {
                localStorage.removeItem('userId');
                next({
                    path: '/register',
                    params: { nextUrl: to.fullPath }
                });
            }
        })
    } else {
        next();
    }
})

export default router
