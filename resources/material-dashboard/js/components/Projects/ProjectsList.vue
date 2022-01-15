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
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Лидов всего</th>
<!--                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">@lang('projects.attributes.leads_today')</th>-->
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Лидов сегодня</th>
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
                    <td>
                        <p class="text-xs font-weight-normal mb-0">{{ project.totalLeads }}</p>
                    </td>
                    <td class="align-middle text-center">
                        <div class="d-flex align-items-center">
                            <span class="me-2 text-xs">{{ project.leadsToday }}</span>
                        </div>
                    </td>
                    <td class="align-middle text-center">
                        <div class="d-flex align-items-center">
                            <span class="me-2 text-xs">{{ project.created_at }}</span>
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
    filteredProject () {
      return this.$store.getters.stateFilteredProjects
    }
  }
}
</script>

<style>
@keyframes showDropMenu {
    from {
        transform: translate(-90%, 0) scale(0.7);
    }
    to {
        transform: translate(-90%, 0) scale(1);
    }
}
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
.projects__dropdown {
    display: block;
    position: relative;
    overflow: visible;
}
.projects__dropdown__menu {
    min-width: 145px;
    padding: 8px 0;
    border-radius: 5px;
    margin: 0;
    position: absolute;
    z-index: 3;
    left: 0;
    top: 0;
    transform: translate(-90%, 0);
    list-style-type: none;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    transition: visibility .25s,opacity .25s,transform .25s;
    cursor: pointer;
    background-color: #ffffff;
    display: none;
    word-wrap: normal;
}
.projects__dropdown__menu--active {
    display: block;
    animation: showDropMenu 0.5s ease;
}
.projects__dropdown__item {
    padding: 4px 16px;
    text-align: left;
    transition: 0.25s;
    position: relative;
    word-wrap: normal
}
.projects__dropdown__title {
    padding: 4px 16px;
    text-align: center;
}
.projects__dropdown__item:hover {
    background-color: #E9ECEF;
}
.table-responsive {
    padding-bottom: 60px !important;
}
.dark-version .projects__dropdown__menu {
    background-color: #202940;
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
}
.dark-version .projects__dropdown__item:hover {
    background-color: #1A2035;
}
</style>
