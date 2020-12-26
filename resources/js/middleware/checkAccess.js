export default function auth ({ next, store, to }) {
    if(store.getters.access == 'close') {
        return next({
            path: '/home',
            params: { nextUrl: to.fullPath }
        });
    } else {
        return next();
    }
}