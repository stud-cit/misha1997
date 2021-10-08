import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: {
          roles_id: null,
          notifications_count: 0,
        },
        access: ''
    },
    mutations: {
        updateNotifications(state, count) {
            state.user.notifications_count = count;
        },
        user_data(state, user) {
            state.user = Object.assign(state.user, user);
        },
        access_mode(state, mode) {
            state.access = mode;
        },
    },
    actions: {
        setUser({commit}, user) {
            commit('user_data', user)
        },
        setAccess({commit}, data) {
          commit('access_mode', data)
      }
    },
    getters: {
        authUser: state => state.user,
        accessMode: state => state.access
    }
})
