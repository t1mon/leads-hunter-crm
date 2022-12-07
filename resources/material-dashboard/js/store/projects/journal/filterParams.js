export default {
  namespaced: true,
  state() {
    return {
      params: {
        date_from: '',
        date_to: '',
        sort_by: '',
        sort_order: '',
        name: '',
        classes: [],
        phone: '',
        entries: ''
      }
    }
  },
  getters: {
    stateParams(state) {
      return state.params
    },
    stateParamsEntries(state) {
      return state.params.entries
    },
    stateParamsClasses(state) {
      return state.params.classes
    },
    stateParamsName(state) {
      return state.params.name
    },
    stateParamsPhone(state) {
      return state.params.phone
    }
  },
  mutations: {
    SET_ENTRIES(state, entries) {
      state.params.entries = entries
      localStorage.setItem('entries', entries)
    },
    SET_PHONE(state, phone) {
      state.params.phone = phone
      localStorage.setItem('phone', phone)
    },
    SET_CLASSES(state, arr) {
      state.params.classes = arr.map(id => {
        return id
      })
      localStorage.setItem('classes', JSON.stringify(arr))
    },
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
        name: '',
        classes: [],
        phone: '',
        entries: ''
      }
      localStorage.removeItem('date_from')
      localStorage.removeItem('date_to')
      localStorage.removeItem('sort_by')
      localStorage.removeItem('sort_order')
      localStorage.removeItem('name')
      localStorage.removeItem('classes')
      localStorage.removeItem('phone')
      localStorage.removeItem('entries')
    }
  }
}
