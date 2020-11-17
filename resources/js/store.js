import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: {
            id: 1,
            guid: '22e6106c-c580-e711-8194-001a4be6d04a',
            job: 'СумДУ',
            name: 'Admin',
            roles_id: 4
        },
        access: 'open',
    },
    mutations: {
        user_data(state, user) {
            state.user = user;
        },
        access_mode(state, mode) {
            state.access = mode;
        },
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
    },
    getters: {
        authUser: state => state.user,
        accessMode: state => state.access
    }
})
