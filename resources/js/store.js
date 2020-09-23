import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        key: localStorage.getItem('key') || '',
        user: JSON.parse(localStorage.getItem('user')) || null,
        access: 'open'
    },
    mutations: {
        auth(state, key) {
            state.token = key
        },
        user_data(state, user) {
            state.user = user
        },
        access_mode(state, mode) {
            state.access = mode
        },
        logout(state) {
            state.key = ''
            state.user = null
        },
    },
    actions: {
        setKey({commit}, key) {
            localStorage.setItem('key', key)
            commit('auth', key)
            axios.defaults.headers.common['Authorization'] = key
        },
        setUser({commit}, user) {
            localStorage.setItem('user', JSON.stringify(user))
            commit('user_data', user)
        },
        getAccess({commit}) {
            axios.get('/api/access')
            .then((response) => {
                commit('access_mode', response.data.value)
            })
        },
        setAccess({commit}, mode) {
            axios.post('/api/access', {
                mode
            }).then(() => {
                commit('access_mode', mode)
            })
        },
        logout({commit}) {
            localStorage.removeItem('user');
            localStorage.removeItem('key');
            commit('logout')
        }
    },
    getters: {
        isLoggedIn: state => !!state.key,
        authUser: state => state.user,
        accessMode: state => state.access,
    }
})
