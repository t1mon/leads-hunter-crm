export default {
  state () {
    return {
      headerSearchPopup: false,
      searchData: null
    }
  },
  getters: {
    stateHeaderSearchPopup: state => {
      return state.headerSearchPopup
    },
    stateSearchData: state => {
      return state.searchData
    }
  },
  mutations: {
    openHeaderSearchPopup (state) {
      state.headerSearchPopup = true
    },
    closeHeaderSearchPopup (state) {
      state.headerSearchPopup = false
    }
  },
  actions: {
    headerSearch ({ state, commit }, value) {
      commit('openHeaderSearchPopup')
      axios.get('/api/v1/search',
        { params: {
            value
          }
        })
        .then((data) => {
          console.log(data)
          state.searchData = data.data.data || null
          console.log(state.searchData)
        })
        .catch(error => {
          console.log(error)
        })
    },
    closeHeaderSearchPopup ({ state, commit }, event) {
      commit('closeHeaderSearchPopup')
    }
  }
}
