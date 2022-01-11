import axios from 'axios'

export default {
  state () {
    return {
      cards: false,
      endpoint: '/api/v1/project',
      isLoading: false,
      projects: null,
      searchProjects: '',
      filteredProjects: null
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
            dispatch('toastDeleteProject')
          }
        }, (error) => {
          console.error(error)
        })

    }
  }
}
