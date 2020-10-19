import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: null,
        access: 'open',
    },
    mutations: {
        user_data(state, user) {
            state.user = user;
        },
        access_mode(state, mode) {
            state.access = mode;
        },
        logout(state) {
            state.user = null;
        }
    },
    actions: {
        setUser({commit}, user) {
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
            axios.post('/api/logout').then(() => {
                commit('logout')
            }).catch(() => {
                console.log("error")
            })
        },
    },
    getters: {
        authUser: state => state.user,
        accessMode: state => state.access
    }
})
