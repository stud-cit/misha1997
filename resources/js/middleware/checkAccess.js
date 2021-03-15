export default function auth ({ next, store, to }) {
    if(store.getters.accessMode == 'close' && store.getters.authUser.roles_id != 4) {
        return next({
            path: '/home',
            params: { nextUrl: to.fullPath }
        });
    } else {
        return next();
    }
}