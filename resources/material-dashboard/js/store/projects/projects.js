import journal from './journal/journal'

export default {
  modules: {
    journal
  },
  state () {
    return {
      cards: false,
      endpoint: '/api/v1/project',
      projects: null,
      searchProjects: '',
      filteredProjects: null,
      projectsLoad: false,
      projectsLeadsCountLoad: false,
      projectsLeadsCount: null
    }
  },
  getters: {
    stateProjectsLeadsCount: state => {
      return state.projectsLeadsCount
    },
    stateCards: state => {
      return state.cards
    },
    stateSearchProjects: state => {
      return state.searchProjects
    },
    stateFilteredProjects: state => {
      return state.filteredProjects
    },
    stateProjectsLoad: state => {
      return state.projectsLoad
    },
    stateProjects: state => {
      return state.projects
    }
  },
  mutations: {
    checkCards (state) {
      state.cards = true
    },
    checkList (state) {
      state.cards = false
    },
    updateMessage (state, value) {
      state.searchProjects = value
    }
  },
  actions: {
    async getProjects ({ state, commit }) {
      commit('loader/LOADER_TRUE', null, { root: true })
      await axios
        .get(state.endpoint)
        .then(({ data }) => {
          commit('loader/LOADER_FALSE', null, { root: true })
          state.projectsLoad = true
          state.projects = data.data
          state.filteredProjects = data.data
        })
        .catch(() => {
          commit('loader/LOADER_FALSE', null, { root: true })
        })
    },
    async getLeadsCount ({ state }) {
      await axios
        .post(state.endpoint + '/leads-count')
        .then(({ data }) => {
          state.projectsLeadsCountLoad = true
          state.projectsLeadsCount = Object.assign({}, ...data.data.map(i => ({ [i.id]: { totalLeads: i.totalLeads, leadsToday: i.leadsToday } })))
        })
        .catch(() => {
          state.projectsLeadsCountLoad = false
        })
    },
    filterProjects ({ state }) {
      state.filteredProjects = state.projects.filter(project => project.name.toLowerCase().includes(state.searchProjects.toLowerCase()))
    },
    dropdown (context, event) {
      event.stopPropagation()
      event.stopImmediatePropagation()
      const projectsDropdownMenuActive = document.querySelectorAll('.projects__dropdown__menu--active')
      const dropMenu = event.currentTarget
      projectsDropdownMenuActive.forEach(item => {
        if (event.currentTarget.parentElement.classList.contains('projects__dropdown__menu--active')) {
          return
        } else {
          item.classList.remove('projects__dropdown__menu--active')
          item.classList.remove('dropdown--active')
        }
      })
      dropMenu.firstElementChild.classList.add('projects__dropdown__menu--active')
      dropMenu.firstElementChild.classList.add('dropdown--active')
      for (let i = 0; i < dropMenu.firstChild.children.length; i++) {
        dropMenu.firstChild.children[i].addEventListener('click', (e) => {
          e.stopPropagation()
          if (e.currentTarget.firstElementChild && e.currentTarget.firstElementChild.classList.contains('projects__dropdown__menu')) {
            return
          } else {
            dropMenu.firstChild.classList.remove('projects__dropdown__menu--active')
            dropMenu.firstChild.classList.remove('dropdown--active')
          }
        })
      }
      document.addEventListener('click', function remActive () {
        dropMenu.firstChild.classList.remove('projects__dropdown__menu--active')
        dropMenu.firstChild.classList.remove('dropdown--active')
        document.removeEventListener('click', remActive)
      })
    },
    deleteProject ({ state, dispatch }, id, event) {
      axios
        .delete(state.endpoint + '/' + id )
        .then(({data}) => {
          if (data.data.response === 200 ) {
            const projectsDropdownMenuActive = document.querySelectorAll('.projects__dropdown__menu--active')
            state.filteredProjects.forEach((project, index) => {
              if (project.id === id) {
                state.projects.splice(index, 1)
              }
            })
            projectsDropdownMenuActive.forEach(item => {
              item.classList.remove('projects__dropdown__menu--active')
            })
            dispatch('getToast', {
              msg: 'Проект удалён!',
              settingsObj: {
                type: 'success',
                position: 'bottom-right',
                timeout: 2000,
                showIcon: true
              }
            })
          }
        }, (error) => {
          console.error(error)
        })
    },
    async switchProject ({ state, dispatch }, id) {
      let projectStatus
      await axios
        .get(state.endpoint + '/' + id + '/' + 'toggle')
        .then(({ data }) => {
          // state.filteredProjects.forEach((project, index) => {
          //   if (project.id === id) {
          //     project.status = !project.status
          //     projectStatus = project.status
          //   }
          // })
          dispatch('getToast', {
            msg: `Проект ${projectStatus ? 'включен' : 'выключен'}!`,
            settingsObj: {
              position: 'bottom-right',
              timeout: 2000,
              showIcon: true
            }
          })
        })
        .catch((error) => {
          console.log(error)
          dispatch('getToast', {
            msg: 'Что-то пошло не так!',
            settingsObj: {
              type: 'danger',
              position: 'bottom-right',
              timeout: 2000,
              showIcon: true
            }
          })
        })
    }
  }
}
