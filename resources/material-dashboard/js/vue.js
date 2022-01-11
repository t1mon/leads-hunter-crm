import { createApp } from 'vue'
import store from './store'
import Projects from './components/Projects/Projects'
import SettingsBar from './components/Settings/SettingsBar'

const app = createApp({
  components: {
    SettingsBar,
    Projects
  },
  mounted () {
    $('[data-confirm]').on('click', () => {
      return confirm($(this).data('confirm'))
    })
  }
})
app.use(store)
app.mount('#app')
