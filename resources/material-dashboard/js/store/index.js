import { createStore } from 'vuex'

// Create a new store instance.
const store = createStore({
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
})

export default store
