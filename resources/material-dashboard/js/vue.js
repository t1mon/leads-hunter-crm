import { createApp } from 'vue'
import Index from './components/Projects/Index'
import FixedPlugin from './components/FixedPlugin'

const app = createApp({
  components: {
    Index,
    FixedPlugin
  },
  mounted() {
    $('[data-confirm]').on('click', () => {
      return confirm($(this).data('confirm'))
    })
  }
}).mount("#app")
