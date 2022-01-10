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
    toastDeleteProject () {
      return createToast('Проект удалён!', {
        type: 'danger',
        position: 'bottom-right',
        timeout: 3000,
        showIcon: true
      })
    }
  }
})

export default store
