import { createApp } from 'vue'
import store from './store'
import Projects from './components/Projects/Projects'
import SettingsBar from './components/Settings/SettingsBar'

const app = createApp({
  components: {
    SettingsBar,
    Projects
  },
  mounted () {
    $('[data-confirm]').on('click', () => {
      return confirm($(this).data('confirm'))
    })
  }
})
app.directive('tLength', {
  mounted (el, binding) {
    const div = document.createElement('div')
    let dots
    div.classList.add('tLength-div')
    el.style.position = 'relative'
    div.textContent = el.textContent

    el.textContent.length > binding.value ? dots = '...' : dots = ''
    const text = el.textContent.substring(0, binding.value) + dots
    el.textContent = text
    el.appendChild(div)
    el.classList.add('tLength')
  }
})
app.use(store)
app.mount('#app')
