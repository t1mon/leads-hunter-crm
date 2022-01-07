<template>
    <div class="col-auto">
        <div class="ms-md-auto d-flex align-items-center">
            <div class="input-group input-group-outline">
                <label class="form-label">Search projects</label>
                <input :disabled="stateIsLoading" @input="filterProjects" v-model="searchProjects" type="text" id="form-control" class="form-control">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "SearchProjects.vue",
    data () {
    },
    methods: {
        filterProjects () {
            return this.$store.dispatch('filterProjects')
        }
    },
    computed: {
        stateIsLoading () {
            return this.$store.getters.stateIsLoading
        },
        searchProjects: {
            get () {
                return this.$store.state.searchProjects
            },
            set (value) {
                this.$store.commit('updateMessage', value)
            }
        }
    }
}
</script>

<style scoped>

</style>
