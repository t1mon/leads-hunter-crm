export default {
  state () {
    return {
      leads: null,
      leadsOrigin: null,
      isLoadingJ: false,
      projectJour: null,
      sortNumber: false,
      sortDate: true,
      sortName: true,
      sortPhone: true,
      sortEntries: true,
      // TODO Найти более элегантный метод
      dataReady: false,
      // Даты из FilterByDate.vue
      dateFrom: '',
      dateTo: ''
    }
  },
  getters: {
    stateDateFrom: state => {
      return state.dateFrom
    },
    stateDateTo: state => {
      return state.dateTo
    },
    stateIsLoadingJ: state => {
      return state.isLoadingJ
    },
    stateLeads: state => {
      return state.leads
    },
    stateProjectJour: state => {
      return state.projectJour
    },
    stateDataReady: state => {
      return state.dataReady
    }
  },
  mutations: {
    switchSpinner (state) {
      state.isLoadingJ = !state.isLoadingJ
    }
  },
  actions: {
    setDateFromTo ({ state }, { dateFrom: _dateFrom, dateTo: _dateTo }) {
      if (_dateFrom) state.dateFrom = _dateFrom
      if (_dateTo) state.dateTo = _dateTo
    },
    sortJournal ({ state }, { param: _param, sortParam: _sortParam, event: _event }) {
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
    getLeads (
      { state, commit, getters, rootState },
      {
        projectId: _projectId,
        dateFrom: _dateFrom,
        dateTo: _dateTo,
        paginateNum: _paginateNum,
        paginatePath: _paginatePath,
        prevNext: _prevNext,
        entriesOperator: _entriesOperator
      }
    ) {
      commit('switchSpinner')
      // считаем сколько лидов поместится
      const rowsNum = Math.floor((document.documentElement.clientHeight - 60) / 49)
        // путь для пагинаций
      const page = _paginatePath ? _paginatePath + '&page=' + _paginateNum : ''
        // номер лида, если перешёл на другую страницу по номеру
      const numberNum = _paginateNum ? (_paginateNum - 1) * rowsNum : 0
      // предыдущая или следующая страница
      const prevNext = _prevNext || ''
      // номер лида, если перешёл на другую страницу по стрелке
      const numberArrow = _prevNext ? (+_prevNext.slice(-1) - 1) * rowsNum : 0
      axios
        .get(rootState.projects.endpoint + '/' + _projectId + '/journal' + page + prevNext,
          {
            params: {
              date_from: _dateFrom,
              date_to: _dateTo,
              rows_num: rowsNum,
              entry_filter: _entriesOperator
            }
          })
        .then(({ data }) => {
          commit('switchSpinner')
          const dataLeads = data.data.leads.data

          // Приводим даты и телефоны в читабельный формат
          dataLeads.forEach((item, index) => {

            // Приводим даты в читабельный формат
            // const addZero = (num) => {
            //   if (num <= 9) {
            //     return '0' + num
            //   } else {
            //     return num
            //   }
            // }
            // const currentDate = new Date(item.created_at)
            // const day = currentDate.getDate()
            // const month = currentDate.getMonth() + 1
            // const year = currentDate.getFullYear()
            // const hours = currentDate.getHours()
            // const minutes = currentDate.getMinutes()
            // const seconds = currentDate.getSeconds()
            // item.created_at = `${addZero(day)}/${addZero(month)}/${addZero(year)} ${addZero(hours)}:${addZero(minutes)}:${addZero(seconds)}`

            // Приводим телефоны в читабельный формат
            item.phone = item.phone.toString().replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, '+7 ($2) $3-$4')

            // Присваиваем новое поле number
            item.number = index + 1 + numberNum + numberArrow
          })

          state.leads = dataLeads
          state.leadsOrigin = dataLeads
          state.projectJour = data.data
          state.dataReady = true
          console.log(data)
        })
        .catch(error => {
          console.log(error)
          commit('switchSpinner')
        })
    }
  }
}
