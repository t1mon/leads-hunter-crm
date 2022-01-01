export default {
  state () {
    return {
      cards: false
    }
  },
  getters: {
    stateCards: state => {
      return state.cards
    }
  },
  mutations: {
    checkCards (state) {
      state.cards = true
    },
    checkList (state) {
      state.cards = false
    }
  }
}
