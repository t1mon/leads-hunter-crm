export default {
  namespaced: true,
  state() {
    return {
      leadId: '',
      manualRegion: ''
    }
  },
  getters: {
    stateLeadId(state) {
      return state.leadId
    },
    stateManualRegion(state) {
      return state.manualRegion
    }
  },
  mutations: {
    SET_LEAD_ID(state, id) {
      state.leadId = id
    },
    SET_MANUAL_REGION(state, region) {
      state.manualRegion = region
    },
    CLEAR_DATA(state) {
      state.leadId = ''
      state.manualRegion = ''
    }
  }
}
