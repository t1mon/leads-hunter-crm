export default {
  namespaced: true,
  state() {
    return {
      leadId: '',
      company: ''
    }
  },
  getters: {
    stateLeadId(state) {
      return state.leadId
    },
    stateCompany(state) {
      return state.company
    }
  },
  mutations: {
    SET_LEAD_ID(state, id) {
      state.leadId = id
    },
    SET_COMPANY(state, company) {
      state.company = company
    },
    CLEAR_DATA(state) {
      state.leadId = ''
      state.company = ''
    }
  }
}
