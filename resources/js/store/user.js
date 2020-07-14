import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
    namespaced: true,
    state: {
        user: []
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
        }
    },
    getters: {
        getUser: state => {
            return state.user;
        }
    }
});