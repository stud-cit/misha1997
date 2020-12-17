import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: {
            notifications: [],
        },
        // user: {
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
        getNotifications: state => {
            return state.user.notifications.filter(item => !item.status).length
        },
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
