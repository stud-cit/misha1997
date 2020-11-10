import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: null,
        access: 'open'
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
        saveFilterUser: ({commit}, filter) => {
            localStorage.setItem('filterUsers', JSON.stringify(filter));
        },
        clearFilterUser: ({commit}) => {
            localStorage.removeItem('filterUsers');
        },
        saveFilterPublications: ({commit}, filter) => {
            localStorage.setItem('filterPublications', JSON.stringify(filter));
        },
        clearFilterPublications: ({commit}) => {
            localStorage.removeItem('filterPublications');
        },
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
        accessMode: state => state.access,
        getFilterUsers() {
            if(localStorage.getItem('filterUsers')) {
                return JSON.parse(localStorage.getItem('filterUsers'));
            }
        },
        getFilterPublications() {
            if(localStorage.getItem('filterPublications')) {
                return JSON.parse(localStorage.getItem('filterPublications'));
            }
        },
    }
})
