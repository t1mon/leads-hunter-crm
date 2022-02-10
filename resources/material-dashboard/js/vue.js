import { createApp } from 'vue'
import store from './store'
import SettingsBar from './components/Settings/SettingsBar'
import Projects from './components/Projects/Projects'
import Journal from './components/Journal/Journal'
import directives from './directives'

const app = createApp({
  components: {
    SettingsBar,
    Projects,
    Journal
  },
  mounted () {
    $('[data-confirm]').on('click', () => {
      return confirm($(this).data('confirm'))
    })
  }
})
directives(app)
app.use(store)
app.mount('#app')
