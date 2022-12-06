export default {
  namespaced: true,
  state() {
    return {
    }
  },
  actions: {
    async getJournalFilters({ state, commit }, obj) {
      commit('switchSpinner', null, { root: true })

      await axios.get('/api/v2/project/' + obj.id + '/journal/variants')
        .then(data => {
          commit('switchSpinner', null, { root: true })
          console.log(data)
        })
        .catch(error => {
          console.log(error)
          commit('switchSpinner', null, { root: true })
        })
    }
  }
}
