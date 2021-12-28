import { createApp } from 'vue'
import store from './store'
import IndexRows from './components/Projects/IndexRows'
import IndexCards from './components/Projects/IndexCards'
import SettingsBar from './components/Settings/SettingsBar'

const app = createApp({
  components: {
    IndexRows,
    IndexCards,
    SettingsBar
  },
  mounted() {
    $('[data-confirm]').on('click', () => {
      return confirm($(this).data('confirm'))
    })
  }
})
app.mount("#app")
app.use(store)
