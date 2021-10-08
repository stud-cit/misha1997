import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

function lazyLoad(view) {
  return() => import(`./views/${view}.vue`)
}

let router = new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: [
      {
        path: '/',
        name: 'home',
        component: lazyLoad('Home')
      },
      {
        path: '/publications',
        name: 'publications',
        component: lazyLoad('Publications')
      },
      {
        path: '/my-publications',
        name: 'my-publications',
        component: lazyLoad('Profile')
      },
      {
        path: '/scopus',
        name: 'scopus',
        component: lazyLoad('PublicationsScopus')
      },
      {
        path: '/archive',
        name: 'archive',
        component: lazyLoad('Archive')
      },
      {
        path: '/rating',
        name: 'rating',
        component: lazyLoad('Rating')
      },
      {
        path: '/constructor',
        name: 'constructor',
        component: lazyLoad('Сonstructor')
      },
      {
        path: '/profile',
        name: 'profile',
        component: lazyLoad('Profile')
      },
      {
        path: '/user/:id',
        name: 'user',
        component: lazyLoad('User')
      },
      {
        path: '/users',
        name: 'users',
        component: lazyLoad('Users')
      },
      {
        path: '/messages',
        name: 'messages',
        component: lazyLoad('Messages')
      },
      {
        path: '/audit',
        name: 'audit',
        component: lazyLoad('Audit')
      },
      {
        path: '/add',
        name: 'add',
        component: lazyLoad('Add')
      },
      {
        path: '/publication/edit/:id',
        name: 'edit',
        component: lazyLoad('Add')
      },
      {
        path: '/publication/:id',
        name: 'publication',
        component: lazyLoad('Publication')
      },
      {
        path: '*',
        name: 'error',
        component: lazyLoad('Error404')
      }
    ]
});

export default router;