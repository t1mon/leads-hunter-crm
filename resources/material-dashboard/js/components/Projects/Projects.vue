<template>
    <div class="projects">
        <div class="projects__row">
            <projects-search></projects-search>

            <projects-tabs></projects-tabs>
        </div>

        <div v-if="stateIsLoading" class="projects__spinner">
            <div  class="spinner-grow" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <div  class="spinner-grow spinner-grow--2" role="status">
            <span class="sr-only">Loading...</span>
            </div>
            <div  class="spinner-grow spinner-grow--3" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <projects-list></projects-list>
        <projects-cards></projects-cards>
    </div>
</template>

<script>
import ProjectsList from './ProjectsList'
import ProjectsCards from './ProjectsCards'
import ProjectsTabs from './ProjectsTabs'
import ProjectsSearch from './ProjectsSearch'

export default {
  name: 'Index',
  components: {
    ProjectsList,
    ProjectsCards,
    ProjectsTabs,
    ProjectsSearch
  },
  computed: {
    getProjects () {
      return this.$store.dispatch('getProjects')
    },
    stateIsLoading () {
      return this.$store.getters.stateIsLoading
    }
  },
  async created () {
    await this.getProjects
  }
}
</script>

<style scoped>
.spinner-grow--2 {
    animation-delay: 0.15s;
}
.spinner-grow--3 {
    animation-delay: 0.3s;
}

.projects {
    position: relative;
}
.projects__spinner {
    position: absolute;
    z-index: 3;
    width: 130px;
    display: flex;
    justify-content: space-between;
    left: 50%;
    top: 100px;
    transform: translateX(-50%);
}

.projects__row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
}

@media screen and (max-width: 575px) {
    .projects__row {
        padding: 10px 0;
    }
}

</style>
