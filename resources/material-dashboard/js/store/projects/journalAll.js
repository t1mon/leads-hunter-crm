export default {
  namespaced: true,
  state() {
    return {
      journal: ''
    }
  },
  actions: {
    async getJournalAll({ state, commit, rootState }, data) {
      commit('switchSpinner', null, { root: true })

      await axios
        .get('/api/v2/project/' + data.id + '/journal', {
          params: {
            date_from: data.dateFrom,
            date_to: data.dateTo
          }
        })
        .then(data => {
          commit('switchSpinner', null, { root: true })
          commit('SET_LEADS', data.data.data.leads.data, { root: true })
          commit('SET_PROJECT_JOUR', data.data.data, { root: true })
          // rootState.leads = data.data.data.leads.data
          console.log(rootState.leads)
          console.log(data)
        })
        .catch(error => {
          commit('switchSpinner', null, { root: true })
          console.log(error)
        })
    }
  }
}
