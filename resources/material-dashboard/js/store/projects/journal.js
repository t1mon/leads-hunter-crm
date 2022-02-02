export default {
  state () {
    return {
      leads: null,
      isLoadingJ: false,
      project: null
    }
  },
  getters: {
    stateIsLoadingJ: state => {
      return state.isLoadingJ
    },
    stateLeads: state => {
      return state.leads
    },
    stateProject: state => {
      return state.project
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
          state.project = data.data
          console.log(data)
        })
        .catch(() => {
          state.isLoadingJ = false
        })
    }
  }
}
