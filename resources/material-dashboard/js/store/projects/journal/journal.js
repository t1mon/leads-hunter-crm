import journalAll from "./journalAll";
import journalFilters from "./journalFilters";
import filterParams from "./filterParams";
import journalComments from "./journalComments";
import journalRegion from "./journalRegion";
import journalCompany from "./journalCompany";

export default {
  modules: {
    journalAll,
    journalFilters,
    filterParams,
    journalComments,
    journalRegion,
    journalCompany
  },
  state () {
    return {
      leads: null,
      leadsOrigin: null,
      // TODO Найти более элегантный метод
      dataReady: false,
      // Даты из JournalPanel.vue,
      projectJour: null
    }
  },
  getters: {
    stateLeads: state => {
      return state.leads
    },
    stateProjectJour: state => {
      return state.projectJour
    },
    stateDataReady: state => {
      return state.dataReady
    }
  },
  mutations: {
    CHANGE_COMMENT_LEAD(state, obj) {
      state.leads[obj.index].comment_crm = obj.comment
    },
    SET_LEADS(state, data) {
      state.leads = ''
      state.leads = data
    },
    SET_PROJECT_JOUR(state, data) {
      state.projectJour = ''
      state.projectJour = data
    }
  },
  actions: {
  }
}
