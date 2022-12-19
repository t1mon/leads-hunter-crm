export default {
  namespaced: true,
  state() {
    return {
      comment: '',
      loader: false,
      leadId: ''
    }
  },
  getters: {
    stateLeadId(state) {
      return state.leadId
    },
    stateLoader(state) {
      return state.loader
    },
    stateComment(state) {
      return state.comment
    }
  },
  mutations: {
    SET_LEAD_ID(state, id) {
      state.leadId = id
    },
    LOADER_TRUE(state) {
      state.loader = true
    },
    LOADER_FALSE(state) {
      state.loader = false
    },
    SET_COMMENT(state, comment) {
      state.comment = comment
    },
    CLEAR_COMMENT(state) {
      state.comment = ''
    }
  },
  actions: {
    commentShow({ state, commit }, id) {
      commit('LOADER_TRUE')
      axios.get('/api/v2/comment/show', {
        params: {
          comment_id: id
        }
      }).then(response => {
        commit('LOADER_FALSE')
        commit('SET_COMMENT', response.data.data.comment_body)
        console.log(response.data.data)
      }).catch(error => {
        commit('LOADER_FALSE')
        console.log(error)
      })
    }
  }
}
