<template>
    <ul class='project__tabs'>
        <li ref="projectList" @click="switchTabs('checkList'), checkCards(false,false)" class="project__tabs__item" :class="{'active' : !this.projectsCards}">
            <i class="material-icons text-lg position-relative">list</i>
            <span class="ms-1">List</span>
        </li>
        <li ref="projectsCards" @click="switchTabs('checkCards'), checkCards(true,true)" class="project__tabs__item" :class="{'active' : this.projectsCards}">
            <i class="material-icons text-lg position-relative">view_module</i>
            <span class="ms-1">Cards</span>
        </li>
        <div ref="projectsCardsActive" :class="{ 'project__tabs__active--cards' : this.projectsCards }" class="project__tabs__active"></div>
    </ul>
</template>

<script>
export default {
  name: 'ProjectsTabs',
  data () {
    return {
      projectsCards: false
    }
  },
  methods: {
    switchTabs (check) { this.$store.commit(check) },
    checkCards (booleanLS, booleanCards) {
      localStorage.setItem('projectsCards', `${booleanLS}`)
      // this.$refs.projectsCardsActive.style.transform = `translateX(${transform}%)`
      this.projectsCards = booleanCards
    }
  },
  mounted: function () {
    if (localStorage.getItem('projectsCards') === 'true') {
      this.$refs.projectsCards.click()
    }
  }
}
</script>

<style scoped>
.project__tabs {
    background: #f8f9fa;
    display: flex;
    align-items: center;
    padding: 0;
    margin: 0;
    border-radius: 0.75rem;
    padding: 8px;
    position: relative;
}
.project__tabs__active {
    position: absolute;
    z-index: 1;
    height: calc(100% - 16px);
    width: calc(50% - 8px);
    transition: all 0.5s ease 0s;
    background-color: #f1f1f1;
    border-radius: 0.5rem;
    left: 8px;
    top: 8px;
    box-shadow: 0 0 5px rgba(0,0,0, 0.15);
}
.project__tabs__active--cards {
    transform: translateX(100%);
}
.project__tabs__item {
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    width: 100px;
    position: relative;z-index: 2;
}
.dark-version .project__tabs {
    background: #202940;
    color: #ffffff;
}
.dark-version .project__tabs__item {
    color: #ffffff;
}
.dark-version .project__tabs .active {
    color: #344667;
    transition: all 0.5s ease 0s;
}

@media screen and (max-width: 575px) {
    .project__tabs {
        flex-direction: column;
    }
    .project__tabs__item {
        justify-content: flex-start;
        width: 80px;
        padding: 0 3px;
    }
    .project__tabs__active {
        width: calc(100% - 16px);
        height: calc(50% - 8px);
    }
    .project__tabs__active--cards {
        transform: translateY(100%);
    }
}
</style>
