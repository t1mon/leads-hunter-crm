<template>
    <div class="row mt-lg-4 mt-2">
        <div v-for="project in filteredProject" class="col-lg-4 col-md-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex mt-n2">
                        <div class="avatar avatar-xl bg-gradient-dark border-radius-xl p-2 mt-n4">
                            <img src="media/img/project.png" alt="slack_logo">
                        </div>
                        <div class="ms-3 my-auto">
                            <a :href="project.link"><h6 class="mb-0">{{ project.name }}</h6></a>
                        </div>
                        <div class="ms-auto">
                            <button @click="dropdown($event)" class="projects__dropdown btn btn-link text-secondary ps-0 pe-2">
                                <ul class="projects__dropdown__menu">
                                    <li class="projects__dropdown__item"><a :href=" project.link ">Журнал</a></li>
                                    <li @click="dropdown($event)" class="projects__dropdown__item">
                                        <ul class="projects__dropdown__menu">
                                            <li class="projects__dropdown__title">Удалить проект?</li>
                                            <li class="projects__dropdown__item">Да</li>
                                            <li class="projects__dropdown__item">Нет</li>
                                        </ul>
                                        <span>Удалить проект</span>
                                    </li>
                                </ul>
                                <i class="fa fa-ellipsis-v text-lg" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-sm mt-3"> If everything I did failed - which it doesn't, I think that it actually succeeds. </p>
                    <hr class="horizontal dark">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="text-sm mb-0">5</h6>
                            <p class="text-secondary text-sm font-weight-normal mb-0">Participants</p>
                        </div>
                        <div class="col-6 text-end">
                            <h6 class="text-sm mb-0">02.03.22</h6>
                            <p class="text-secondary text-sm font-weight-normal mb-0">Due date</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
  name: 'ProjectsCards',
    methods: {
        dropdown (event) {
            return this.$store.dispatch('dropdown', event)
        }
    },
  computed: {
    filteredProject () {
      return this.$store.getters.stateFilteredProjects
    }
  }
}

</script>

<style scoped>
.dropdown-menu::before {
    display: none;
}
</style>
