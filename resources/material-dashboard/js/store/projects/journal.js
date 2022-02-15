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
    switchSpinner (state) {
      state.isLoadingJ = !state.isLoadingJ
    }
  },
  actions: {
    getLeads ({ state, commit, rootState }, id) {
      commit('switchSpinner')
      axios
        .get(rootState.projects.endpoint + '/' + id + '/journal')
        .then(({ data }) => {
          commit('switchSpinner')
          state.leads = data.data.leads.data
          state.project = data.data
          console.log(data)
        })
        .catch(() => {
          commit('switchSpinner')
        })
    }
  }
}
