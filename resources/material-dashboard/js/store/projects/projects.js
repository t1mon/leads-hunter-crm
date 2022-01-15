import axios from 'axios'

export default {
  state () {
    return {
      cards: false,
      endpoint: '/api/v1/project',
      isLoading: false,
      projects: null,
      searchProjects: '',
      filteredProjects: null,
      projectsLoad: false
    }
  },
  getters: {
    stateCards: state => {
      return state.cards
    },
    stateIsLoading: state => {
      return state.isLoading
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
    getProjects ({ state }) {
      state.isLoading = true
      axios
        .get(state.endpoint)
        .then(({ data }) => {
          state.isLoading = false
          state.projectsLoad = true

          const dateParse = function (date) {
            const addZero = (num) => {
              if (num <= 9) {
                return '0' + num
              } else {
                return num
              }
            }
            const currentDate = new Date(date)
            const day = currentDate.getDate()
            const month = currentDate.getMonth() + 1
            const year = currentDate.getFullYear()
            const hours = currentDate.getHours()
            const minutes = currentDate.getMinutes()
            const seconds = currentDate.getSeconds()
            return `${addZero(day)}/${addZero(month)}/${addZero(year)} ${addZero(hours)}:${addZero(minutes)}:${addZero(seconds)}`
          }

          data.data.forEach(obj => {
            obj.created_at = dateParse(obj.created_at)
          })
          state.projects = data.data
          state.filteredProjects = data.data
          console.log(data.data)
        })
        .catch(() => {
          state.isLoading = false
        })
    },
    filterProjects ({ state }) {
      state.filteredProjects = state.projects.filter(project => {
        return project.name.toLowerCase().indexOf(state.searchProjects.toLowerCase()) !== -1
      })
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
        }
      })
      dropMenu.firstElementChild.classList.add('projects__dropdown__menu--active')
      for (let i = 0; i < dropMenu.firstChild.children.length; i++) {
        dropMenu.firstChild.children[i].addEventListener('click', (e) => {
          e.stopPropagation()
          if (e.currentTarget.firstElementChild && e.currentTarget.firstElementChild.classList.contains('projects__dropdown__menu')) {
            console.log(1)
            return
          } else {
            dropMenu.firstChild.classList.remove('projects__dropdown__menu--active')
          }
        })
      }
      document.addEventListener('click', function remActive () {
        dropMenu.firstChild.classList.remove('projects__dropdown__menu--active')
        document.removeEventListener('click', remActive)
      })
    },
    deleteProject ({ state, dispatch }, id, event) {
      axios
        .delete(state.endpoint + '/' + id )
        .then(({data}) => {
          console.log(data)
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
    switchProject ({ state, dispatch }, id) {
      let projectStatus
      axios
        .get(state.endpoint + '/' + id + '/' + 'toggle')
        .then(({ data }) => {
          state.filteredProjects.forEach((project, index) => {
            if (project.id === id) {
              project.status = !project.status
              projectStatus = project.status
            }
          })
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
