import Vue from "vue";
import Router from "vue-router";
import store from './store.js'

import auth from './middleware/auth';
import guest from './middleware/guest';
import register from './middleware/register';

import Home from "./components/Home";
import Auth from "./components/Auth";
import Profile from "./components/Profile";
import User from "./components/User";
import Publications from "./components/Publications/Index";
import MyPublications from "./components/Publications/My";
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
            component: Auth,
            meta: {
                middleware: guest
            }
        },
        {
            path: '/cabinet*',
            name: 'cabinet',
            component: Home,
            meta: {
                middleware: auth
            }
        },
        {
            path: '/home',
            name: 'home',
            component: Home,
            meta: {
                middleware: auth
            }
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
            meta: {
                middleware: register
            }
        },
        {
            path: '/profile',
            name: 'profile',
            component: Profile,
            meta: {
                middleware: auth
            }
        },
        {
            path: '/user/:id',
            name: 'user',
            component: User,
            meta: {
                middleware: auth
            }
        },
        {
            path: '/publications',
            name: 'publications',
            component: Publications,
            meta: {
                middleware: auth
            }
        },
        {
            path: '/my-publications',
            name: 'my-publications',
            component: MyPublications,
            meta: {
                middleware: auth
            }
        },
        {
            path: '/publications/add',
            name: 'publications-add',
            component: PublicationsAdd,
            // meta: {
            //     middleware: auth
            // }
        },
        {
            path: '/publications/:id',
            component: PublicationsView,
            meta: {
                middleware: auth
            }
        },
        {
            path: '/publications/edit/:id',
            name: 'publications-edit',
            component: PublicationsEdit,
            meta: {
                middleware: auth
            }
        },
        {
            path: '/notifications',
            name: 'notifications',
            component: Notifications,
            meta: {
                middleware: auth
            }
        },
        {
            path: '/users',
            name: 'users',
            component: Users,
            meta: {
                middleware: auth
            }
        },
        {
            path: '*',
            name: 'error',
            component: Error404,
        }

    ]
});

function nextFactory(context, middleware, index) {
    const subsequentMiddleware = middleware[index];
    if (!subsequentMiddleware) return context.next;
    return (...parameters) => {
        context.next(...parameters);
        const nextMiddleware = nextFactory(context, middleware, index + 1);
        subsequentMiddleware({ ...context, next: nextMiddleware });
    };
}

router.beforeEach((to, from, next) => {
    if (to.meta.middleware) {
        const middleware = Array.isArray(to.meta.middleware) ? to.meta.middleware : [to.meta.middleware];
        const context = { from, next, router, to, store };
        const nextMiddleware = nextFactory(context, middleware, 1);
        return middleware[0]({ ...context, next: nextMiddleware });
    }
    return next();
});

export default router
