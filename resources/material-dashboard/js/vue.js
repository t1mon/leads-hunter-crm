import { createApp } from 'vue'
import store from './store'
import SettingsBar from './components/Settings/SettingsBar'
import Projects from './components/Projects/Projects'
import Journal from './components/Journal/Journal'

const app = createApp({
  components: {
    SettingsBar,
    Projects,
    Journal
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
app.directive('date', {
  mounted (el, binding) {
    const addZero = (num) => {
      if (num <= 9) {
        return '0' + num
      } else {
        return num
      }
    }
    const currentDate = new Date(binding.value)
    const day = currentDate.getDate()
    const month = currentDate.getMonth() + 1
    const year = currentDate.getFullYear()
    const hours = currentDate.getHours()
    const minutes = currentDate.getMinutes()
    const seconds = currentDate.getSeconds()
    el.textContent = `${addZero(day)}/${addZero(month)}/${addZero(year)} ${addZero(hours)}:${addZero(minutes)}:${addZero(seconds)}`
  }
})
app.directive('tel', {
  mounted (el, binding) {
    const tel = binding.value.toString().replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, '+7 ($2) $3-$4')
    el.textContent = tel
  }
})
app.use(store)
app.mount('#app')
