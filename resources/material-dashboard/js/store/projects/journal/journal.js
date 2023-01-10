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
      // Даты из JournalPanel.vue
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
    SET_LEADS(state, data) {
      state.leads = data
    },
    SET_PROJECT_JOUR(state, data) {
      state.projectJour = data
    }
  },
  actions: {
  }
}
