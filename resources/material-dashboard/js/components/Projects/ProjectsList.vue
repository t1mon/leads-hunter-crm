<template>
<!--    <div class="row">-->
<!--        <div class="col-12">-->
<!--            <div class="card my-4">-->
<!--                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">-->
<!--                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-2 pb-2 pt-sm-4 pb-sm-3 ">-->
<!--                        <h6 class="text-white text-capitalize ps-3">Projects table-->
<!--                        </h6>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="card-body px-0 pb-2">-->
<!--                    <div class="table-responsive p-0">-->
<!--                        <table class="table align-items-center justify-content-center mb-0">-->
<!--                            <thead>-->
<!--                            <tr>-->
<!--                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Project</th>-->
<!--                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Budget</th>-->
<!--                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>-->
<!--                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Completion</th>-->
<!--                                <th></th>-->
<!--                            </tr>-->

<!--                            </thead>-->
<!--                            <tbody>-->
<!--                            <tr v-for="project in filteredProject">-->
<!--                                <td>-->
<!--                                    <div class="d-flex px-2">-->
<!--                                        <div>-->
<!--                                            <img src="/assets/img/small-logos/logo-asana.svg" class="avatar avatar-sm rounded-circle me-2" alt="spotify">-->
<!--                                        </div>-->
<!--                                        <div class="my-auto">-->
<!--                                            <a :href=" project.link "><h6 class="mb-0 text-sm"  >{{ project.name }}</h6> </a>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </td>-->
<!--                                <td>-->
<!--                                    <p class="text-sm font-weight-bold mb-0">$2,500</p>-->
<!--                                </td>-->
<!--                                <td>-->
<!--                                    <span class="text-xs font-weight-bold">working</span>-->
<!--                                </td>-->
<!--                                <td class="align-middle text-center">-->
<!--                                    <div class="d-flex align-items-center justify-content-center">-->
<!--                                        <span class="me-2 text-xs font-weight-bold">60%</span>-->
<!--                                        <div>-->
<!--                                            <div class="progress">-->
<!--                                                <div class="progress-bar bg-gradient-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </td>-->
<!--                                <td class="align-middle">-->
<!--                                    <div class="dropdown">-->
<!--                                        <button data-bs-toggle="dropdown" class="btn btn-link text-secondary mb-0">-->
<!--                                            <i class="fa fa-ellipsis-v text-xs" aria-hidden="true"></i>-->
<!--                                        </button>-->
<!--                                        <div class="dropdown-menu dropdown-menu-end me-sm-n4 me-n3" aria-labelledby="navbarDropdownMenuLink1" style="">-->
<!--                                            <a class="dropdown-item" :href="project.link">Журнал</a>-->
<!--                                            <a class="dropdown-item" href="javascript:;">Another action</a>-->
<!--                                            <a class="dropdown-item" href="javascript:;">Something else here</a>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                            </tbody>-->
<!--                        </table>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

    <div class="card">
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                <tr>
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
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="project in filteredProject">
                    <td>
                        <div class="d-flex px-2">
                            <div>
                                <img src="media/img/project.png" class="avatar avatar-sm rounded-circle me-2">
                            </div>
                            <div class="my-auto">
                                <a :href=" project.link "><h6 class="mb-0 text-sm"  >{{ project.name }}</h6> </a>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span v-if="project.status" class="badge badge-dot me-4">
                            <i class="bg-success"></i>
                            <span class="text-dark text-xs">Активен</span>
                        </span>
                        <span v-if="!project.status" class="badge badge-dot me-4">
                            <i class="bg-danger"></i>
                            <span class="text-dark text-xs">Приостановлен</span>
                        </span>
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
                               <li class="projects__dropdown__item">Lorem ipsum</li>
                               <li class="projects__dropdown__item">Lorem ipsum</li>
                               <li @click="dropdown($event)" class="projects__dropdown__item">
                                   <ul class="projects__dropdown__menu">
                                       <li class="projects__dropdown__title">Удалить проект?</li>
                                       <li class="projects__dropdown__item">Да</li>
                                       <li class="projects__dropdown__item">Нет</li>
                                   </ul>
                                   <span>Удалить проект</span>
                               </li>
                            </ul>
                            <span class="material-icons">more_vert</span>
                        </button>
                    </td>
                    <td>
                        <a href="{{ route('project.journal', $project) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>
                    </td>
                    <td>

<!--                        {!! Form::model($project, ['method' => 'DELETE', 'route' => ['project.destroy', $project], 'class' => 'form-inline', 'data-confirm' => __('forms.projects.delete')]) !!}-->
<!--                        {!! Form::button('<i class="fa fa-trash" aria-hidden="true"></i>', ['class' => 'btn btn-danger btn-sm', 'name' => 'submit', 'type' => 'submit']) !!}-->
<!--                        {!! Form::close() !!}-->
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

<style>
@keyframes showDropMenu {
    from {
        transform: translate(-50%, -50%) scale(0.7);
    }
    to {
        transform: translate(-50%, -50%) scale(1);
    }
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
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
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
