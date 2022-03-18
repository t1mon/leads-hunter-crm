import { createStore } from 'vuex'
import axios from 'axios'
import { createToast } from 'mosha-vue-toastify'
import 'mosha-vue-toastify/dist/style.css'
import projects from './projects/projects'
import headerSearch from './header/search'

// Create a new store instance.
const store = createStore({
  modules: {
    projects,
    headerSearch
  },
  actions: {
    getToast (context, { msg, settingsObj }) {
      return createToast(msg, settingsObj)
    }
  }
})

export default store
