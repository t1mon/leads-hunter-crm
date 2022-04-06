<template>
    <div class="card">
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                <tr>
                    <th></th>
<!--                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">@lang('projects.attributes.name')</th>-->
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Имя</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">status</th>
<!--                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">@lang('projects.attributes.leads_all')</th>-->
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Лидов всего</th>
<!--                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">@lang('projects.attributes.leads_today')</th>-->
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Лидов сегодня</th>
<!--                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">@lang('projects.attributes.created_at')</th>-->
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Дата создания</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="project in filteredProject">
                    <td>
                        <div class="d-flex justify-content-center px-2">
                            <div class="form-check form-switch ps-0">
                                <input @change="switchProject(project.id)" class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault" :checked="project.status">
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex px-2">
                            <div v-avatar="{ background: project.color, name: project.name }" class="projects__card__avatar"></div>
                            <div class="my-auto">
                                <a :href=" project.link "><h6 class="mb-0 text-sm"  >{{ project.name }}</h6> </a>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="projects__card__status">
                        <span v-if="project.status" class="badge badge-dot me-4">
                            <i class="bg-success"></i>
                            <span class="text-dark text-xs">Активен</span>
                        </span>
                            <span v-if="!project.status" class="badge badge-dot me-4">
                            <i class="bg-danger"></i>
                            <span class="text-dark text-xs">Приостановлен</span>
                        </span>
                        </div>
                    </td>
                    <td class="text-center">
<!--                        <p class="text-xs font-weight-normal mb-0">{{ project.totalLeads }}</p>-->
                        <p v-if="stateProjectsLeadsCount" class="text-xs font-weight-normal mb-0">{{ stateProjectsLeadsCount[project.id].totalLeads }}</p>
                        <div v-if="!stateProjectsLeadsCount" class="spinner-border text-default m-auto" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex align-items-center justify-content-center">
<!--                            <span class="me-2 text-xs">{{ project.leadsToday }}</span>-->
                            <span v-if="stateProjectsLeadsCount" class="me-2 text-xs">{{ stateProjectsLeadsCount[project.id].leadsToday }}</span>
                            <div v-if="!stateProjectsLeadsCount" class="spinner-border text-default m-auto" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle text-center">
                        <div class="d-flex align-items-center">
                            <span v-date="project.created_at" class="me-2 text-xs"></span>
                        </div>
                    </td>
                    <td class="align-middle">
                        <button @click="dropdown($event)" class="projects__dropdown btn btn-link text-secondary mb-0">
                            <ul class="projects__dropdown__menu">
                               <li class="projects__dropdown__item"><a :href=" project.link ">Журнал</a></li>
                               <li @click="dropdown($event)" class="projects__dropdown__item">
                                   <ul class="projects__dropdown__menu">
                                       <li class="projects__dropdown__title">Удалить проект?</li>
                                       <li @click="deleteProject(project.id, $event)" class="projects__dropdown__item">Да</li>
                                       <li class="projects__dropdown__item">Нет</li>
                                   </ul>
                                   <span>Удалить проект</span>
                               </li>
                            </ul>
                            <span class="material-icons">more_vert</span>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>

export default {
  name: 'ProjectsList',
    methods: {
        dropdown (event) {
            event.stopPropagation()
            event.stopImmediatePropagation()
            return this.$store.dispatch('dropdown', event)
        },
        deleteProject (id, event) {
            event.stopPropagation()
            event.stopImmediatePropagation()
            return this.$store.dispatch('deleteProject', id)
        },
        switchProject (id) {
            return this.$store.dispatch('switchProject', id)
        }
    },
  computed: {
      stateProjectsLeadsCount () {
          return this.$store.getters.stateProjectsLeadsCount
      },
    filteredProject () {
      return this.$store.getters.stateFilteredProjects
    }
  }
}
</script>

<style>

.projects__card__avatar {
    border-radius: 0.75rem;
    width: 74px;
    height: 74px;
    margin-right: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: #ffffff;
}
.projects__card__status {
    width: 101px;
}

</style>
