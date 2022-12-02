export default {
  namespaced: true,
  state() {
    return {
      params: {
        date_from: '',
        date_to: '',
        sort_by: '',
        sort_order: '',
        name: ''
      }
    }
  },
  getters: {
    stateParams(state) {
      return state.params
    }
  },
  mutations: {
    SET_NAME(state, name) {
      state.params.name = name
      localStorage.setItem('name', name)
    },
    SET_DATE_FROM(state, date) {
      state.params.date_from = date
      localStorage.setItem('date_from', date)
    },
    SET_DATE_TO(state, date) {
      state.params.date_to = date
      localStorage.setItem('date_to', date)
    },
    SET_SORT_BY(state, val) {
      state.params.sort_by = val
      localStorage.setItem('sort_by', val)
    },
    SET_SORT_ORDER(state, val) {
      state.params.sort_order = val
      localStorage.setItem('sort_order', val)
    },
    CLEAR_PARAMS(state) {
      state.params = {
        date_from: '',
        date_to: '',
        sort_by: '',
        sort_order: '',
        name: ''
      }
      localStorage.removeItem('date_from')
      localStorage.removeItem('date_to')
      localStorage.removeItem('sort_by')
      localStorage.removeItem('sort_order')
      localStorage.removeItem('name')
    }
  }
}
