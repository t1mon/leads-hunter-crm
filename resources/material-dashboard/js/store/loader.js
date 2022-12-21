export default {
  namespaced: true,
  state() {
    return {
      loader: false
    }
  },
  getters: {
    stateLoader(state) {
      return state.loader
    }
  },
  mutations: {
    LOADER_TRUE(state) {
      state.loader = true
    },
    LOADER_FALSE(state) {
      state.loader = false
    }
  }
}
