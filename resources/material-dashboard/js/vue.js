import { createApp } from 'vue'
import store from './store'
import List from './components/Projects/List'
import Cards from './components/Projects/Cards'
import Tabs from './components/Projects/Tabs'
import SearchProjects from './components/Projects/SearchProjects'
import SettingsBar from './components/Settings/SettingsBar'

const app = createApp({
  components: {
    List,
    Cards,
    Tabs,
    SettingsBar,
    SearchProjects
  },
  mounted() {
    $('[data-confirm]').on('click', () => {
      return confirm($(this).data('confirm'))
    })
  }
})
app.use(store)
app.mount("#app")
