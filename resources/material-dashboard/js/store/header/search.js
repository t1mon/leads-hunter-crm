export default {
  state () {
    return {
      headerSearchPopup: false
    }
  },
  getters: {
    stateHeaderSearchPopup: state => {
      return state.headerSearchPopup
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
