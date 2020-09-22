import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        key: localStorage.getItem('key') || '',
        user: localStorage.getItem('user') || null,
    },
    mutations: {
        auth(state, key) {
            state.token = key
        },
        user_data(state, user) {
            state.user = user
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
        logout({commit}) {
            localStorage.removeItem('user');
            localStorage.removeItem('key');
            commit('logout')
        }
    },
    getters: {
        isLoggedIn: state => !!state.key,
        authUser: state => state.user,
    }
})