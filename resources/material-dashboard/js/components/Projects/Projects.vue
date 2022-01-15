<template>
    <div>
        <div v-if="stateIsLoading" class="projects__spinner-wrap">
            <div class="projects__spinner"></div>
        </div>
        <div v-show="stateProjectsLoad && stateProjects.length > 0" class="projects">
            <div class="projects__row">
                <projects-search></projects-search>

                <projects-tabs></projects-tabs>
            </div>

            <projects-list ref="hide" class="projects__content--show" v-if="!checkCards"></projects-list>
            <projects-cards ref="hide" class="projects__content--show" v-if="checkCards"></projects-cards>
        </div>
        <div v-if="stateProjectsLoad && stateProjects.length === 0">
            <h2 class="projects__title projects__title--empty">Нет ни одного проекта!</h2>
            <div class="col-md-12 my-auto text-center">
                <a href="/project/create" class="btn bg-gradient-primary mb-0 mt-0 mt-lg-0">
                    <i class="material-icons text-white position-relative text-md pe-2">add</i> Добавить первый проект
                </a>
            </div>
        </div>
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
    },
    checkCards () {
      return this.$store.getters.stateCards
    },
    stateProjectsLoad () {
      return this.$store.getters.stateProjectsLoad
    },
    stateProjects () {
      return this.$store.getters.stateProjects
    }
  },
  async created () {
    await this.getProjects
  }
}
</script>

<style scoped>
@keyframes show {
    0% { transform: perspective(400px) translateZ(-100px); }
    100% { transform: none; }
}

@keyframes spin {
    0% {transform: rotate(0deg);}
    100% {transform: rotate(360deg);}
}

.projects__title--empty {
    text-align: center;
    padding-top: 40px;
    margin-bottom: 30px;
}

.projects__spinner {
    position: absolute;
    height: 60px;
    width: 60px;
    border: 3px solid transparent;
    border-top-color: #A04668;
    top: 50%;
    left: 50%;
    margin: -30px;
    border-radius: 50%;
    animation: spin 2s linear infinite;
}

.projects__spinner::before, .projects__spinner::after {
    content:'';
    position: absolute;
    border: 3px solid transparent;
    border-radius: 50%;
}

.projects__spinner::before{
    border-top-color: #254E70;
    top: -12px;
    left: -12px;
    right: -12px;
    bottom: -12px;
    animation: spin 3s linear infinite;
}

.projects__spinner::after{
    border-top-color: #FFFBFE;
    top: 6px;
    left: 6px;
    right: 6px;
    bottom: 6px;
    animation: spin 4s linear infinite;
}

.projects__content--show {
    animation: show 0.5s ease;
}

.projects {
    position: relative;
}

.projects__spinner-wrap {
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 199;
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
