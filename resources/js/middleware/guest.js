export default function guest ({ next, to }) {
    axios.get('/api/check-user')
    .then((response) => {
        if(response.data.status == 'register') {
            return next({
                path: '/home',
                params: { nextUrl: to.fullPath }
            });
        } else if(response.data.status == 'login') {
            return next({
                path: '/register',
                params: { nextUrl: to.fullPath }
            });
        } else {
            return next();
        }
    })
}