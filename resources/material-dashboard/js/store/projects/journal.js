export default {
  state () {
    return {
      leads: null,
      leadsOrigin: null,
      isLoadingJ: false,
      project: null,
      sortNumber: false,
      sortDate: true,
      sortName: true,
      sortPhone: true,
      sortEntries: true
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
    sortJournalEntries ({ state }, { first: _first, second: _second }) {
      if (_first && !_second) {
        state.leads = state.leadsOrigin
        state.leads = state.leads.filter(entry => entry.entries === 1)
      } else if (!_first && _second) {
        state.leads = state.leadsOrigin
        state.leads = state.leads.filter(entry => entry.entries > 1)
      } else {
        state.leads = state.leadsOrigin
      }
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
    getLeads ({ state, commit, rootState }, { projectId: _projectId, dateFrom: _dateFrom, dateTo: _dateTo }) {
      commit('switchSpinner')
      axios
        .get(rootState.projects.endpoint + '/' + _projectId + '/journal', { params: {
            date_from: _dateFrom,
            date_to: _dateTo
          }
        })
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
          state.leadsOrigin = dataLeads
          state.project = data.data
          console.log(data)
        })
        .catch(error => {
          console.log(error)
          commit('switchSpinner')
        })
    }
  }
}
