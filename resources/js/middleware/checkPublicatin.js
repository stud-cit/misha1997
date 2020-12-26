export default function auth ({ next, store, to }) {
    if(store.getters.authUser.roles_id == 1) {
        axios.get('/api/check-publication/'+to.params.id)
        .then((response) => {
            if(response.data.status == 'ok') {
                return next();
            } else {
                return next({
                    path: '/home',
                    params: { nextUrl: to.fullPath }
                });
            }
        })
    } else {
        return next();
    }
}