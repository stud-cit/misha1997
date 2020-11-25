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
        access: 'open'
    },
    mutations: {
        updateNotifications(state, item) {
            var index = state.user.notifications.indexOf(item);
            state.user.notifications.splice(index, 1);
        },
        user_data(state, user) {
            state.user = user;
        },
        access_mode(state, mode) {
            state.access = mode;
        },
        deleteItemNotifications(state, notification) {
            state.user.notifications.find(item => item.id == notification.id).status;
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
        getNotifications: state => {
            return state.user.notifications.filter(item => !item.status).length
        },
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