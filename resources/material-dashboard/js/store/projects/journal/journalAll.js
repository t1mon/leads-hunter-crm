export default {
  namespaced: true,
  state() {
    return {
      journal: '',
      projectId: ''
    }
  },
  getters: {
    stateProjectId(state) {
      return state.projectId
    }
  },
  mutations: {
    SET_PROJECT_ID(state, id) {
      state.projectId = id
    }
  },
  actions: {
    async getJournalAll({ state, getters, commit, rootState, rootGetters }, data) {
      commit('switchSpinner', null, { root: true })
      const url = `/api/v2/project/${getters.stateProjectId}/journal`

      //Данные с хранилища vuex
      const filterParams = rootGetters['filterParams/stateParams']

      const params = {}

      //Данные с localStorage
      const date_fromLS = localStorage.getItem('date_from')
      const date_toLS = localStorage.getItem('date_to')
      const sort_byLS = localStorage.getItem('sort_by')
      const sort_orderLS = localStorage.getItem('sort_order')
      const nameLS = localStorage.getItem('name')

      if (date_fromLS && date_toLS) {
        params.date_from = date_fromLS
        params.date_to = date_toLS
      }
      if (sort_byLS && sort_orderLS) {
        params.sort_by = sort_byLS
        params.sort_order = sort_orderLS
      }
      if (nameLS) params.name = nameLS

      if (filterParams.date_from) {
        params.date_from = filterParams.date_from
        params.date_to = filterParams.date_to
      }
      if (filterParams.sort_by) {
        params.sort_by = filterParams.sort_by
        params.sort_order = filterParams.sort_order
      }
      if (filterParams.name) params.name = filterParams.name
      if (data && data.page) params.page = data.page
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
