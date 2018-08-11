import Vue from 'vue'
import Vuex from 'vuex'
import VuexPersist from 'vuex-persist'
// import Cookies from 'js-cookie'

Vue.use(Vuex)

const vuexLocalStorage = new VuexPersist({
  key: 'vuex',
  storage: window.localStorage,
  strict: false
})

export default new Vuex.Store({
  strict: true,
  state: {
    token: null,
    user: null,
    userLogged: false
  },
  mutations: {
    setToken (state, token) {
      state.token = token
      if (token) {
        state.userLogged = true
      } else {
        state.userLogged = false
      }
    },
    setUser (state, user) {
      state.user = user
    },
    logout (state) {
      state.user = null
      state.token = null
      state.userLogged = false
    }
  },
  getters: {
    isLoggedIn (state) {
      return state.userLogged
    },
    getToken (state) {
      return state.token
    },
    getUser (state) {
      return state.user
    }
  },
  actions: {
    setToken ({commit}, token) {
      commit('setToken', token)
    },
    setUser ({commit}, user) {
      commit('setUser', user)
    },
    logout ({commit}) {
      commit('logout')
    }
  },
  plugins: [vuexLocalStorage.plugin]
})
