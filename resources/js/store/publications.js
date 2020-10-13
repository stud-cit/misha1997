import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        publication: {}
    },
    mutations: {
        setPublication(state, data) {
            state.publication = Object.assign(state.publication, data)
            console.log(state.publication)
        },
    },
    getters: {
        getPublication: state => state.publication
    }
})
