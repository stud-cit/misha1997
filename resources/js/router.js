import Vue from "vue";
import Router from "vue-router";
import store from './store.js'

import Home from "./components/Home";
import Auth from "./components/Auth";
import Profile from "./components/Profile";
import User from "./components/User";
import Publications from "./components/Publications/Index";
import PublicationsAdd from "./components/Publications/Add";
import PublicationsView from "./components/Publications/View";
import PublicationsEdit from "./components/Publications/Edit";
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
                requiresRegister: false,
                roles: [1, 2, 3, 4]
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
            path: '/profile',
            name: 'profile',
            component: Profile,
            meta: {
                requiresRegister: false,
            }
        },
        {
            path: '/user/:id',
            name: 'user',
            component: User,
            meta: {
                requiresRegister: false,
            }
        },
        {
            path: '/publications',
            name: 'publications',
            component: Publications,
            meta: {
                requiresRegister: false,
                roles: [1, 2, 3, 4]
            }
        },
        {
            path: '/publications/add',
            name: 'publications-add',
            component: PublicationsAdd,
            meta: {
                requiresRegister: false,
                roles: [1, 2, 3, 4]
            }
        },
        {
            path: '/publications/:id',
            component: PublicationsView,
            meta: {
                requiresRegister: false,
                roles: [1, 2, 3, 4]
            }
        },
        {
            path: '/publications/edit/:id',
            name: 'publications-edit',
            component: PublicationsEdit,
            meta: {
                requiresRegister: false,
                roles: [1, 2, 3, 4]
            }
        },
        {
            path: '/notifications',
            name: 'notifications',
            component: Notifications,
            meta: {
                requiresRegister: false,
                roles: [1, 2, 3, 4]
            }
        },
        {
            path: '/users',
            name: 'users',
            component: Users,
            meta: {
                requiresRegister: false,
                roles: [2, 3, 4]
            }
        },
        {
            path: '*',
            name: 'error',
            component: Error404,
        }

    ]
});

// router.beforeEach((to, from, next) => {
//     if(to.matched.some(record => record.meta.requiresAuth)) {
//         if(!store.getters.authUser) {
//             next();
//         } else {
//             next({
//                 path: '/',
//                 params: { nextUrl: to.fullPath }
//             });
//         }
//     } else if (to.matched.some(record => record.meta.requiresRegister)) {
//         axios.get('/api/check-user')
//         .then((response) => {
//             if(response.data.status == 'register') {
//                 store.dispatch('setUser', response.data.user)
//                 next();
//             } else if(response.data.status == 'login') {
//                 next({
//                     path: '/register',
//                     params: { nextUrl: to.fullPath }
//                 });
//             } else {
//                 store.dispatch('logout')
//                 next({
//                     path: '/',
//                     params: { nextUrl: to.fullPath }
//                 });
//             }
//         })
//     } else {
//         next();
//     }
// })

export default router
