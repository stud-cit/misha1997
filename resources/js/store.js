import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: {
            id: 1,
            roles_id: 4,
            notifications_count: 0
        },
        access: 'open'
    },
    mutations: {
        updateNotifications(state, count) {
            state.user.notifications_count = count;
        },
        user_data(state, user) {
            state.user = user;
        },
        access_mode(state, mode) {
            state.access = mode;
        },
    },
    actions: {
        saveFilterUser: ({commit}, filter) => {
            sessionStorage.setItem('filterUsers', JSON.stringify(filter));
        },
        clearFilterUser: ({commit}) => {
            sessionStorage.removeItem('filterUsers');
        },
        saveFilterPublications: ({commit}, filter) => {
            sessionStorage.setItem('filterPublications', JSON.stringify(filter));
        },
        clearFilterPublications: ({commit}) => {
            sessionStorage.removeItem('filterPublications');
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
            if(sessionStorage.getItem('filterUsers')) {
                return JSON.parse(sessionStorage.getItem('filterUsers'));
            }
        },
        getFilterPublications() {
            if(sessionStorage.getItem('filterPublications')) {
                return JSON.parse(sessionStorage.getItem('filterPublications'));
            }
        },
    }
})
