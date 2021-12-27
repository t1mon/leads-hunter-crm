import { createApp } from 'vue'
import Index from './components/Projects/Index'
import SettingsBar from './components/Settings/SettingsBar'

const app = createApp({
  components: {
    Index,
    SettingsBar
  },
  mounted() {
    $('[data-confirm]').on('click', () => {
      return confirm($(this).data('confirm'))
    })
  }
}).mount("#app")
