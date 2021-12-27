import { createApp } from 'vue'

const app = createApp({
  mounted() {
    $('[data-confirm]').on('click', () => {
      return confirm($(this).data('confirm'))
    })
  }
}).mount("#app")
