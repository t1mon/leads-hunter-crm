import { createStore } from 'vuex'
import projects from './projects/projects'

// Create a new store instance.
const store = createStore({
  modules: {
    projects
  }
})

export default store
