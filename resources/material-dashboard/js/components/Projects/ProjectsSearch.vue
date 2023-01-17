<template>
  <div class="col-auto filter">
    <div class="ms-md-auto d-flex align-items-center">
      <div class="input-group input-group-outline">
        <label class="form-label">Filter projects</label>
        <input
          id="form-control"
          v-model="filter"
          :disabled="stateIsLoading"
          type="text"
          class="form-control"
        >
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProjectsFilter',
  data: () => ({
    filter: ''
  }),
  computed: {
    stateIsLoading () {
      return this.$store.getters.stateIsLoading
    }

  },
  watch: {
    filter () {
      window.history.pushState(null, document.title, `${window.location.pathname}?filter=${this.filter}`)
      this.$store.commit('updateMessage', this.filter)
      this.$store.dispatch('filterProjects')
    },
    stateIsLoading () {
      const windowData = Object.fromEntries(
        new URL(window.location).searchParams.entries()
      )
      if (windowData.filter) {
        this.filter = windowData.filter
      }
    }
  }
}
</script>

<style scoped>
@media screen and (max-width: 575px) {
    .filter {
        width: 150px;
    }
}
</style>
