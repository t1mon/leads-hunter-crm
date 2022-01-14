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
    div.textContent = el.textContent
    el.classList.add('tLength')
    el.textContent.length > binding.value ? dots = '...' : dots = ''
    const text = el.textContent.substring(0, binding.value) + dots
    el.textContent = text
    el.appendChild(div)
  }
})
app.directive('avatar', {
  mounted (el, binding) {
    const arr = binding.value.name.split(' ')
    let name
    if (arr.length > 1) {
      name = `${arr[0].charAt(0).toUpperCase()} ${arr[1].charAt(0).toUpperCase()}`
    } else {
      name = `${arr[0].charAt(0).toUpperCase()}`
    }
    el.style.backgroundColor = '#' + binding.value.background
    el.textContent = name
  }
})
app.use(store)
app.mount('#app')
