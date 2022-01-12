import { createStore } from 'vuex'
import { createToast } from 'mosha-vue-toastify'
import 'mosha-vue-toastify/dist/style.css'
import projects from './projects/projects'

// Create a new store instance.
const store = createStore({
  modules: {
    projects
  },
  actions: {
    getToast (context, { msg, settingsObj }) {
      return createToast(msg, settingsObj)
    }
  }
})

export default store
