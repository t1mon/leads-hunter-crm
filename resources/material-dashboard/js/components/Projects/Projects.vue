<template>
    <div class="projects">
        <div class="projects__row">
            <projects-search></projects-search>

            <projects-tabs></projects-tabs>
        </div>

        <div v-if="stateIsLoading" class="projects__spinner-wrap">
            <div class="projects__spinner"></div>
        </div>

        <projects-list ref="hide" class="projects__content--show" v-if="!checkCards"></projects-list>
        <projects-cards ref="hide" class="projects__content--show" v-if="checkCards"></projects-cards>
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
