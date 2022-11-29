export default {
  namespaced: true,
  state() {
    return {
      params: {
        date_from: '',
        date_to: ''
      }
    }
  },
  getters: {
    stateParams(state) {
      return state.params
    }
  },
  mutations: {
    SET_DATE_FROM(state, date) {
      state.params.date_from = date
    },
    SET_DATE_TO(state, date) {
      state.params.date_to = date
    }
  }
}
