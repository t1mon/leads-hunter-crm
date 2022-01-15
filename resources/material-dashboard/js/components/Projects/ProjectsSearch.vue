<template>
    <div class="col-auto search">
        <div class="ms-md-auto d-flex align-items-center">
            <div class="input-group input-group-outline">
                <label class="form-label">Search projects</label>
                <input @input="filterProjects" v-model="searchProjects" type="text" id="form-control" class="form-control">
            </div>
        </div>
    </div>
</template>

<script>
export default {
  name: 'ProjectsSearch',
  computed: {
    searchProjects: {
      get () {
        return this.$store.state.searchProjects
      },
      set (value) {
        this.$store.commit('updateMessage', value)
      }
    }
  },
  methods: {
    filterProjects () {
      return this.$store.dispatch('filterProjects')
    }
  }
}
</script>

<style scoped>
@media screen and (max-width: 575px) {
    .search {
        width: 150px;
    }
}
</style>
