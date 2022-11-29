export default {
  namespaced: true,
  state() {
    return {
      journal: ''
    }
  },
  actions: {
    async getJournalAll({ state, commit, rootState, rootGetters }, data) {
      commit('switchSpinner', null, { root: true })
      const url = `/api/v2/project/${data.id}/journal`
      const filterParams = rootGetters['filterParams/stateParams']
      const params = {}

      if (filterParams.date_from) {
        params.date_from = filterParams.date_from
        params.date_to = filterParams.date_to
      }
      if (data.page) params.page = data.page
      await axios
        .get(url, {
          params: params
        })
        .then(data => {
          commit('switchSpinner', null, { root: true })
          commit('SET_LEADS', data.data.data.leads.data, { root: true })
          commit('SET_PROJECT_JOUR', data.data.data, { root: true })
          console.log(data)
        })
        .catch(error => {
          commit('switchSpinner', null, { root: true })
          console.log(error)
        })
    }
  }
}
