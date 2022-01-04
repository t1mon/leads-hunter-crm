import axios from 'axios'

export default {
  state () {
    return {
      cards: false,
      endpoint: '/api/v1/project',
      isLoading: false,
      projects: null
    }
  },
  getters: {
    stateCards: state => {
      return state.cards
    },
    stateIsLoading: state => {
      return state.isLoading
    },
    stateProjects: state => {
      return state.projects
    }
  },
  mutations: {
    checkCards (state) {
      state.cards = true
    },
    checkList (state) {
      state.cards = false
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
        })
        .catch(() => {
          state.isLoading = false
        })
    }
  }
}
