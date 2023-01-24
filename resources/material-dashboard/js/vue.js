import { createApp } from 'vue'
import store from './store'

//components
import SettingsBar from './components/Settings/SettingsBar'
import SettingsBasic from './components/Settings/SettingsBasic/SettingsBasic'
import Projects from './components/Projects/Projects'
import Journal from './components/Journal/Journal'
import HeaderSearch from './components/Header/Search'
import NavbarProjects from './components/Navbar/NavbarProjects'
import LoaderApp from './components/Others/Spinner'
import directives from './directives'

const app = createApp({
  components: {
    SettingsBar,
    SettingsBasic,
    Projects,
    Journal,
    HeaderSearch,
    NavbarProjects,
    LoaderApp
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
