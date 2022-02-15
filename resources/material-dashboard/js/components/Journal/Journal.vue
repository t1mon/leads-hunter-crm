<template>
    <div>
        <div class="row my-4">
            <div class="col-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Дата</th>
                                <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Клиент</th>
                                <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Класс</th>
                                <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Телефон</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">№</th>
                                <th class=" text-uppercase text-center text-xxs font-weight-bolder opacity-7">Комментарий</th>

                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">E-MAIl</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">Город</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">Сумма сделки</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">Посадочная</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">Реферрер</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">[UTM_SOURCE]</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">[UTM_MEDIUM]</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">[UTM_CAMPAIGN]</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">ИСТОЧНИК</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(lead, index) in stateLeads">
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <p class="text-sm font-weight-normal mb-0">{{ index + 1 }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p v-date="lead.created_at" class="text-center text-sm font-weight-normal mb-0"></p>
                                </td>
                                <td :style="'background:' + ' ' + '#' + leadColor(lead.class)">
                                    <h6 class="text-center mb-0 font-weight-normal text-sm">{{  lead.name }}</h6>
                                </td>
                                <td class="text-white text-center">
                                    <div v-select class="select">
                                        <span class="select__title">{{ className(lead.class, 'Не задан') }}</span>
                                        <span class="material-icons select__arrow">expand_more</span>
                                        <div class="select__content">
                                            <div @click="colorDefault($event)" class="select__option">Не задан</div>
                                            <div v-for="projectClass in stateProject.classes" @click="color($event, projectClass.color), getLeadClass(stateProject.id, lead.id, projectClass.id)" class="select__option">
                                                <div class="journal__row">
                                                    <span class="journal__class-name">{{ projectClass.name }}</span>
                                                    <span :style="'background:' + ' ' + '#' + projectClass.color" class="journal__class-color"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <p v-tel="lead.phone" class="mb-0 font-weight-normal text-sm"></p>
                                </td>
                                <td>
                                    <div class="text-center">
                                      <span class="badge badge-dot">
                                          <i v-if="lead.entries === 1" class="bg-success"></i>
                                          <i v-if="lead.entries === 2" class="bg-warning"></i>
                                          <i v-if="lead.entries > 2" class="bg-danger"></i>
                                        <span class="text-dark text-xs"> {{ lead.entries }}</span>
                                      </span>
                                    </div>
                                </td>

                                <td class="align-middle text-center text-sm">
                                    <a v-if="lead.comment_crm[1]" :href="'/project/project/' + stateProject.id + '/' + lead.id + '/comment/' + lead.comment_crm[0]" v-tLength="25">{{ lead.comment_crm[1] }}</a>
                                    <a v-if="!lead.comment_crm[1]" :href="'/project/project/' + stateProject.id + '/' + lead.id + '/comment/create'"><span class="material-icons">add</span></a>
                                </td>

                                <td v-tLength="25" class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.email }}
                                </td>
                                <td class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.city }}
                                </td>
                                <td class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.cost }}
                                </td>
                                <td class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.host }}
                                </td>
                                <td v-tLength="25" class="text-sm font-weight-normal mb-0">
                                    {{ lead.referrer }}
                                </td>
                                <td class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.utm[0] }}
                                </td>
                                <td class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.utm[1] }}
                                </td>
                                <td class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.utm[2] }}
                                </td>
                                <td v-tLength="25" class="text-sm font-weight-normal mb-0">
                                    {{ lead.source }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <spinner v-if="stateIsLoadingJ"></spinner>
    </div>
</template>

<script>
import Spinner from '../Others/Spinner'

export default {
    name: "Journal",
    components: {
        Spinner
    },
    props: ['projectid'],
    methods: {
        async getLeadClass (projectId, leadId, classId) {
            const store = this.$store
            store.commit('switchSpinner')
            await axios.post('/project/' + projectId + '/journal/' + leadId + '/class/assign', {
                class_id: classId
            })
            .then(function (response) {
                console.log(response)
                store.commit('switchSpinner')
            })
            .catch(function (error) {
                store.commit('switchSpinner')
                console.log(error)
            })
        },
        className (leadClass, defaultName) {
            let name
            if (leadClass) {
                name = leadClass.name
            } else {
                name = defaultName
            }
            return name
        },
        leadColor (leadClass) {
            let color
            if (leadClass) {
                color = leadClass.color
            } else {
                color = ''
            }
          return color
        },
        colorDefault (event) {
            event.currentTarget.closest('td').previousElementSibling.style.background = ''
        },
        color (event, color) {
            event.currentTarget.closest('td').previousElementSibling.style.background = '#' + color
        },
        getLeads (id) {
            return this.$store.dispatch('getLeads', id)
        }
    },
    computed: {
        stateIsLoadingJ () {
            return this.$store.getters.stateIsLoadingJ
        },
        stateLeads () {
            return this.$store.getters.stateLeads
        },
        stateProject () {
            return this.$store.getters.stateProject
        }
    },
    async created () {
        await this.getLeads(this.projectid)
    }
}
</script>

<style scoped>
.journal__row {
    display: flex;
    justify-content: space-between;
}
.journal__class-name {
    width: calc(100% - 20px);
    white-space: normal;
}
.journal__class-color {
    width: 15px;
    height: 15px;
    border-radius: 2px;
}
.table-responsive {
    padding-top: 50px;
}
</style>
