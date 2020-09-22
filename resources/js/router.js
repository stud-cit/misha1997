import Vue from "vue";
import Router from "vue-router";
import store from './store.js'

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
                requiresRegister: true
            }
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/profile/:id',
            name: 'profile',
            component: Profile,
            meta: {
                requiresRegister: true
            }
        },
        {
            path: '/publications',
            name: 'publications',
            component: Publications,
            meta: {
                requiresRegister: true
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
        if(!store.getters.authUser) {
            next();
        } else {
            next({
                path: '/',
                params: { nextUrl: to.fullPath }
            });
        }
    } else if (to.matched.some(record => record.meta.requiresRegister)) {
        axios.get('/api/check-user')
        .then((response) => {
            if(response.data.status == 'register') {
                store.dispatch('setUser', response.data.user)
                next();
            } else if(response.data.status == 'login') {
                next({
                    path: '/register',
                    params: { nextUrl: to.fullPath }
                });
            } else {
                store.dispatch('logout')
                next({
                    path: '/',
                    params: { nextUrl: to.fullPath }
                });
            }
        })
    } else {
        next();
    }
})

export default router
