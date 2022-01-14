<template>
    <div class="row mt-lg-4 mt-2">
        <div v-for="project in filteredProject" class="projects__card__wrap col-md-8 col-lg-6 col-xxl-5 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex mt-n2">
                        <div v-avatar="{ background: project.color, name: project.name }" class="projects__card__avatar avatar avatar-xl bg-gradient-dark border-radius-xl p-2 mt-n4"></div>
                        <div class="ms-3 my-auto projects__card__name__wrap">
                            <a :href="project.link" class="projects__card__name"><h6 v-tLength="10" class="mb-0">{{ project.name }}</h6></a>
                        </div>
                        <div class="projects__card__status mx-auto my-auto">
                            <span v-if="project.status" class="badge badge-dot">
                                <i class="bg-success"></i>
                                <span class="text-dark text-xs">Активен</span>
                            </span>
                                <span v-if="!project.status" class="badge badge-dot">
                                <i class="bg-danger"></i>
                                <span class="text-dark text-xs">Приостановлен</span>
                            </span>
                        </div>
                        <div class="align-items-center d-flex justify-content-center px-2">
                            <div class="form-check form-switch ps-0">
                                <input @change="switchProject(project.id)" class="form-check-input ms-auto" type="checkbox" id="flexSwitchCheckDefault" :checked="project.status">
                            </div>
                        </div>
                        <div class="ms-auto">
                            <button @click="dropdown($event)" class="projects__dropdown btn btn-link text-secondary ps-0 pe-2">
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
                                <i class="fa fa-ellipsis-v text-lg" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <hr class="horizontal dark">

                    <div class="projects__card__row">
                        <div class="projects__card__box">
                            <div class="projects__card__box__header">
                                <div class="projects__card__box__status">
                                    <span v-if="project.emailSend.enabled" class="badge badge-dot">
                                        <i class="bg-success"></i>
                                    </span>
                                    <span v-if="!project.emailSend.enabled" class="badge badge-dot">
                                        <i class="bg-danger"></i>
                                    </span>
                                </div>
                                <h5 class="projects__card__box__title">E-mail:</h5>
                            </div>

                            <ul class="projects__card__box__content">
                                <li v-tLength="16" v-for="email in project.emailSend.emailsList" class="projects__card__box__item">{{ email[0] }}</li>
                            </ul>
                        </div>

                        <div class="projects__card__line"></div>

                        <div class="projects__card__box">
                            <div class="projects__card__box__header">
                                <h5 class="projects__card__box__title">Вебхуки:</h5>
                            </div>

                            <ul class="projects__card__box__content projects__card__box__content--right">
                                <li v-for="webhook in project.webhooks" class="projects__card__box__item">
                                    <div class="projects__card__box__status">
                                        <span v-if="webhook.enabled" class="projects__card__dot badge badge-dot">
                                            <i class="bg-success"></i>
                                        </span>
                                        <span v-if="!webhook.enabled" class="projects__card__dot badge badge-dot">
                                            <i class="bg-danger"></i>
                                        </span>
                                    </div>
                                    <span>{{ webhook.name }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <hr class="horizontal dark">
                    <div class="row">
                        <div class="col-4">
                            <h6 class="text-sm mb-0">{{ project.totalLeads }}</h6>
                            <p class="text-secondary text-sm font-weight-normal mb-0">Лидов всего</p>
                        </div>
                        <div class="col-4">
                            <h6 class="text-sm mb-0">{{ project.leadsToday }}</h6>
                            <p class="text-secondary text-sm font-weight-normal mb-0">Лидов сегодня</p>
                        </div>
                        <div class="col-4 text-end">
                            <h6 class="text-sm mb-0">{{ project.created_at }}</h6>
                            <p class="text-secondary text-sm font-weight-normal mb-0">Дата создания</p>
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

<style scoped>
.projects__card__avatar {
    background-image: none;
    font-weight: 600;
}
.projects__card__status {
    min-width: 101px;
}

.projects__card__name {
    overflow: hidden;
}
.projects__card__line {
    position: absolute;
    width: 2px;
    background-color: #7b809a;
    border-radius: 50%;
    height: 100%;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
}
.projects__card__row {
    display: flex;
    justify-content: space-between;
    width: 100%;
    position: relative;
}
.projects__card__box {
    width: 45%;
    word-wrap: break-word;
}
.projects__card__box__header {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}
.projects__card__box__title {
    margin: 0;
}
.projects__card__box__content {
    padding: 0;
    padding-left: 12px;
    margin: 0;
    list-style-type: none;
}
.projects__card__box__content--right {
    padding: 0;
}
.projects__card__box__item {
    display: flex;
    line-height: 110%;
    margin-bottom: 5px;
}
.projects__card__dot {
    padding: 0;
}
@media screen and (min-width: 1700px) {
    .projects__card__wrap {
        width: 33.3%;
    }
}
@media screen and (max-width: 450px) {
    .projects__card__box {
        overflow: hidden;
    }
}
@media screen and (max-width: 380px) {
    .projects__card__status {
        min-width: 0;
    }
    .projects__card__name {
        display: block;
        max-width: 45px;
        word-wrap: normal;
    }
    .projects__card__name__wrap {
        margin-left: 0 !important;
    }
}
</style>
