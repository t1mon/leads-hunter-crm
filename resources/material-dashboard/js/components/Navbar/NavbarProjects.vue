<template>
    <ul class="nav ">
        <li
            v-for="project in projectsActive"
            :class="{ 'active' : stateProjectId === project.id }"
            class="nav-item">
            <a
                :href="'/project/' + project.id + '/journal'"
                :class="{ 'active' : stateProjectId === project.id }"
                style="white-space: normal"
                class="align-items-start nav-link text-white m-0 rounded-0 p-2 mw-100"
            >
                <span class="sidenav-mini-icon font-weight-bolder me-1">{{project.id}})</span>
                <span class="">{{project.name}}</span>
            </a>
        </li>
        <div class="bg-secondary w-100 py-2">
            <div class="form-check m-0 d-flex align-items-center">
                <input v-model="showInactive" class="form-check-input m-0" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label font-weight-bolder text-white mb-0" for="flexCheckDefault">
                    Показать неактивные
                </label>
            </div>
        </div>
        <div v-if="showInactive" class="opacity-5">
            <li
                v-for="project in projectsInactive"
                :class="{ 'active' : stateProjectId === project.id }"
                class="nav-item">
                <a
                    :href="'/project/' + project.id + '/journal'"
                    :class="{ 'active' : stateProjectId === project.id }"
                    style="white-space: normal"
                    class="align-items-start nav-link text-white m-0 rounded-0 p-2 mw-100"
                >
                    <span class="sidenav-mini-icon font-weight-bolder me-1">{{project.id}})</span>
                    <span class="">{{project.name}}</span>
                </a>
            </li>
        </div>
    </ul>
</template>
<script>
export default {
    name: "NavbarJournal",
    props: {
        projects: {
            type: Array,
            required: true
        }
    },
    data() {
      return {
          showInactive: true
      }
    },
    computed: {
        stateProjectId() {
            return this.$store.getters['journalAll/stateProjectId']
        },
        projectsActive() {
            const projects = this.projects.filter(el => {
                return el.settings.enabled
            })
            return projects
        },
        projectsInactive() {
            const projects = this.projects.filter(el => {
                return !el.settings.enabled
            })
            return projects
        }
    }
}
</script>

<style scoped>

</style>
