import { createApp } from 'vue'
import store from './store'
import IndexList from './components/Projects/IndexList'
import IndexCards from './components/Projects/IndexCards'
import IndexTabs from './components/Projects/IndexTabs'
import SettingsBar from './components/Settings/SettingsBar'

const app = createApp({
  components: {
    IndexList,
    IndexCards,
    IndexTabs,
    SettingsBar
  },
  mounted() {
    $('[data-confirm]').on('click', () => {
      return confirm($(this).data('confirm'))
    })
  }
})
app.use(store)
app.mount("#app")
