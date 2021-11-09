import Vue from 'vue'

Vue.config.productionTip = false

window.VueEvent = new Vue()

new Vue({
  el: '#app',

  components: {

  },

  mounted () {
    $('[data-confirm]').on('click', () => {
      return confirm($(this).data('confirm'))
    })
  }
})
