export default function directives (app) {
  let counterDocListener = 0
  // Регулирует длину текста.
  // Выведет полный текст сверху при наведении мыши.
  // Передайте количество символов: v-tLength="8"
  // Так же можно отключить вывод полного текста: v-tLength="{length: 8, notDiv: true}"
  app.directive('tLength', {
    mounted (el, binding) {
      const div = document.createElement('div')
      let value
      let dots
      if (typeof binding.value === 'object') {
        value = binding.value.length
      } else {
        value = binding.value
      }
      div.classList.add('tLength-div')
      el.classList.add('tLength')
      div.textContent = el.textContent
      el.textContent.length > value ? dots = '...' : dots = ''
      const text = el.textContent.substring(0, value) + dots
      el.textContent = text
      if (!binding.value.notDiv && el.textContent.length > value) {
        el.appendChild(div)
      }
    }
  })

  app.directive('tLengthDyn', {
    mounted (el, binding) {
      const div = document.createElement('div')
      div.classList.add('tLength-div')
      el.classList.add('tLength')
      let dots
      let _text
      const text = binding.value.text
      const length = binding.value.length
      div.textContent = text
      if (text) {
        text.length > length ? dots = '...' : dots = ''
        _text = text.substring(0, length) + dots
      }
      el.textContent = _text
      if (el.textContent.length > length) {
        el.appendChild(div)
      } else {
        div.remove()
      }
    },
    updated (el, bind) {
      let div = el.querySelector('.tLength-div')
      const text = bind.value.text
      const length = bind.value.length
      let dots
      let _text
      if (div) {
        div.textContent = text
      } else {
        div = document.createElement('div')
        div.classList.add('tLength-div')
        div.textContent = text
      }
      if (text) {
        text.length > length ? dots = '...' : dots = ''
        _text = text.substring(0, length) + dots
      }
      el.textContent = _text
      if (el.textContent.length > length) {
        el.appendChild(div)
      } else {
        div.remove()
      }
    }
  })

  app.directive('test', {
    mounted (el, binding) {
      el.textContent = binding.value
    },
    updated (el, binding) {
      el.textContent = binding.value
    }
  })

  // Принимает цвет и строку, задаёт фон элемента соответствующим цветом и выводит первую букву строки:
  // v-avatar="{ background: '5F9EA0', 'Егор' }
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

  // Приводит дату в читабельный формат
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

  // Приводит телфон в читабельный формат
  app.directive('tel', {
    mounted (el, binding) {
      const tel = binding.value.toString().replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, '+7 ($2) $3-$4')
      el.textContent = tel
    }
  })

  // Создаёт кастомный селект, но нужна соответствующая вёрстка
  app.directive('select', {
    mounted (el, binding) {
      const content = el.lastElementChild
      let options = content.children
      options = Array.prototype.slice.call(options) // Теперь options - массив
      let contentHeight = 0
      options.forEach(item => {
        contentHeight += item.clientHeight
      })

      function closeSelects () {
        document.querySelectorAll('.select--active').forEach(item => {
          item.classList.remove('select--active')
          item.lastElementChild.style.maxHeight = '0px'
        })
      }
      options.forEach(item => {
        item.addEventListener('click', (e) => {
          e.stopPropagation()
          el.firstElementChild.textContent = item.textContent
          closeSelects()
          document.removeEventListener('click', closeSelects)
        })
      })
      el.addEventListener('click', (e) => {
        e.stopPropagation()
        document.querySelectorAll('.dropdown--active').forEach(item => {
          item.classList.remove('dropdown--active')
        })
        if (el.classList.contains('select--active')) {
          closeSelects()
        } else {
          closeSelects()
          el.classList.add('select--active')
          if (binding.value) {
            content.style.maxHeight = binding.value + 'px'
          } else {
            content.style.overflow = 'hidden'
            content.style.maxHeight = contentHeight + 5 + 'px'
          }
          if (counterDocListener === 0) {
            counterDocListener++
            document.addEventListener('click', function _closeSelects () {
              closeSelects()
              document.removeEventListener('click', _closeSelects)
              counterDocListener = 0
            })
          }
        }
      })
    }
  })
}
