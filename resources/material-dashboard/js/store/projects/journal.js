export default {
  state () {
    return {
      leads: null,
      isLoadingJ: false,
      project: null,
      leadsReverse: false,
      sortDate: true,
      sortName: true
    }
  },
  getters: {
    stateIsLoadingJ: state => {
      return state.isLoadingJ
    },
    stateLeads: state => {
      return state.leads
    },
    stateProject: state => {
      return state.project
    }
  },
  mutations: {
    switchSpinner (state) {
      state.isLoadingJ = !state.isLoadingJ
    }
  },
  actions: {
    journalReverse ({ state }, event) {
      if (state.leadsReverse) {
        event.currentTarget.textContent = 'По убыванию'
      } else {
        event.currentTarget.textContent = 'По возрастанию'
      }
      state.leads.reverse()
      state.leadsReverse = !state.leadsReverse
    },
    sortJournal ({ state }, { param: _param, sortParam: _sortParam, event: _event }) {
      console.log()
      if (state[_sortParam]) {
        state.leads.sort((a, b) => {
          if (a[_param] > b[_param]) {
            return 1
          }
          if (a[_param] < b[_param]) {
            return -1
          }
          // a должно быть равным b
          return 0
        })
        _event.currentTarget.textContent = 'По убыванию'
      } else {
        state.leads.sort((a, b) => {
          if (a[_param] < b[_param]) {
            return 1
          }
          if (a[_param] > b[_param]) {
            return -1
          }
          // a должно быть равным b
          return 0
        })
        _event.currentTarget.textContent = 'По возрастанию'
      }
      state[_sortParam] = !state[_sortParam]
    },
    getLeads ({ state, commit, rootState }, id) {
      commit('switchSpinner')
      axios
        .get(rootState.projects.endpoint + '/' + id + '/journal')
        .then(({ data }) => {
          commit('switchSpinner')
          const dataLeads = data.data.leads.data

          // Приводим даты и телефоны в читабельный формат
          dataLeads.forEach((item, index) => {

            // Приводим даты в читабельный формат
            const addZero = (num) => {
              if (num <= 9) {
                return '0' + num
              } else {
                return num
              }
            }
            const currentDate = new Date(item.created_at)
            const day = currentDate.getDate()
            const month = currentDate.getMonth() + 1
            const year = currentDate.getFullYear()
            const hours = currentDate.getHours()
            const minutes = currentDate.getMinutes()
            const seconds = currentDate.getSeconds()
            item.created_at = `${addZero(day)}/${addZero(month)}/${addZero(year)} ${addZero(hours)}:${addZero(minutes)}:${addZero(seconds)}`

            // Приводим телефоны в читабельный формат
            item.phone = item.phone.toString().replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, '+7 ($2) $3-$4')

            // Присваиваем новое поле number
            item.number = index + 1
          })

          state.leads = dataLeads
          state.project = data.data
          console.log(data)
        })
        .catch(() => {
          commit('switchSpinner')
        })
    }
  }
}
