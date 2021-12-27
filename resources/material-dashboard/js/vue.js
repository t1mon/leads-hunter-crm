import { createApp } from 'vue'
import Index from './components/Projects/Index'

const app = createApp({
  components: {
    Index
  },
  mounted() {
    $('[data-confirm]').on('click', () => {
      return confirm($(this).data('confirm'))
    })
  }
}).mount("#app")
