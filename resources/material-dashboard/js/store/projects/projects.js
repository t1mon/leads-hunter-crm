import axios from 'axios'

export default {
  state () {
    return {
      cards: false,
      endpoint: '/api/v1/project',
      isLoading: false,
      projects: null,
      searchProjects: '',
      filteredProjects: null
    }
  },
  getters: {
    stateCards: state => {
      return state.cards
    },
    stateIsLoading: state => {
      return state.isLoading
    },
    stateSearchProjects: state => {
      return state.searchProjects
    },
    stateFilteredProjects: state => {
      return state.filteredProjects
    }
  },
  mutations: {
    checkCards (state) {
      state.cards = true
    },
    checkList (state) {
      state.cards = false
    },
    updateMessage (state, value) {
      state.searchProjects = value
    }
  },
  actions: {
    getProjects ({ state }) {
      state.isLoading = true
      axios
        .get(state.endpoint)
        .then(({ data }) => {
          state.isLoading = false
          state.projects = data.data
          state.filteredProjects = data.data
        })
        .catch(() => {
          state.isLoading = false
        })
    },
    filterProjects ({ state }) {
      state.filteredProjects = state.projects.filter(project => {
        return project.name.toLowerCase().indexOf(state.searchProjects.toLowerCase()) !== -1
      })
    }
  }
}
