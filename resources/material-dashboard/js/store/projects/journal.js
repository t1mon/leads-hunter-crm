export default {
  state () {
    return {
      leads: null,
      isLoadingJ: false
    }
  },
  getters: {
    stateIsLoadingJ: state => {
      return state.isLoadingJ
    },
    stateLeads: state => {
      return state.leads
    }
  },
  mutations: {

  },
  actions: {
    getLeads ({ state, rootState }, id) {
      state.isLoadingJ = true
      axios
        .get(rootState.projects.endpoint + '/' + id + '/journal')
        .then(({ data }) => {
          state.isLoadingJ = false
          state.leads = data.data.leads.data
          console.log(data)
        })
        .catch(() => {
          state.isLoadingJ = false
        })
    }
  }
}
