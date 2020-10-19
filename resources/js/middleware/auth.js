export default function auth ({ next, store, to }) {
    axios.get('/api/check-user')
    .then((response) => {
        if(response.data.status == 'register') {
            store.dispatch('setUser', response.data.user)
            return next();
        } else if(response.data.status == 'login') {
            return next({
                path: '/register',
                params: { nextUrl: to.fullPath }
            });
        } else {
            store.dispatch('setUser', null);
            return next({
                path: '/',
                params: { nextUrl: to.fullPath }
            });
        }
    })
}