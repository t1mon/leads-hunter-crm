export default {
  namespaced: true,
  state() {
    return {
    }
  },
  actions: {
    async getJournalFilters({ state, commit }, obj) {
      commit('loader/LOADER_TRUE', null, { root: true })

      await axios.get('/api/v2/project/' + obj.id + '/journal/variants')
        .then(data => {
          commit('loader/LOADER_FALSE', null, { root: true })
        })
        .catch(error => {
          console.log(error)
          commit('loader/LOADER_FALSE', null, { root: true })
        })
    }
  }
}
