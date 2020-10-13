import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        key: localStorage.getItem('key') || '',
        user: JSON.parse(localStorage.getItem('user')) || null,
        // {
        //     academic_code: null,
        //     categ_1: null,
        //     categ_2: null,
        //     country: null,
        //     created_at: null,
        //     department_code: null,
        //     email: "admin@gmail.com",
        //     faculty_code: null,
        //     guid: "22e6106c-c580-e711-8194-001a4be6d04a",
        //     h_index: null,
        //     id: 1,
        //     job: "СумДУ",
        //     name: "Admin",
        //     orcid: null,
        //     roles_id: 4,
        //     scopus_autor_id: null,
        //     scopus_researcher_id: null,
        //     updated_at: null
        // },
        access: 'open',
        publication: {}
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
        setPublication(state, data) {
            state.publication = Object.assign(state.publication, data)
            // console.log(state.publication)
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
        getPublication: state => state.publication
    }
})
