<template>
    <div>
        <div class="row my-4">
            <div class="col-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                <th
                                    @click="dropdownFilter({
                                        event: $event,
                                        coords: { left: '0' }
                                    })"
                                    class="cursor-pointer text-uppercase text-xxs font-weight-bolder"
                                >
                                    <span>#</span>
                                    <div class="journal__sort">
                                        <div class="journal__sort__content">
                                            <span @click="journalReverse($event)">По убыванию</span>
                                        </div>
                                        <div class="journal__sort__before"></div>
                                    </div>
                                </th>

                                <th @click="dropdownFilter({
                                        event: $event,
                                        coords: { left: '0' }
                                    })"
                                    class="cursor-pointer text-center text-uppercase text-xxs font-weight-bolder"
                                >
                                    <span>Дата</span>
                                    <div class="journal__sort">
                                        <div class="journal__sort__content">
                                            <span @click="sortJournal('updated_at', 'sortDate', $event)">По возрастанию</span>
                                        </div>
                                        <div class="journal__sort__before"></div>
                                    </div>
                                </th>
                                <th @click="dropdownFilter( {event: $event} )" class="cursor-pointer text-center text-uppercase text-xxs font-weight-bolder">
                                    <span>Клиент</span>
                                    <div class="journal__sort">
                                        <div class="journal__sort__content">
                                            <span clas="journal__filter__" @click="sortJournal('name', 'sortName', $event)">По возрастанию</span>
                                        </div>
                                        <div class="journal__sort__before"></div>
                                    </div>
                                </th>
                                <th class="cursor-pointer text-center text-uppercase text-xxs font-weight-bolder">Класс</th>
                                <th class="cursor-pointer text-center text-uppercase text-xxs font-weight-bolder">Телефон</th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">№</th>
                                <th class="cursor-pointer text-uppercase text-center text-xxs font-weight-bolder">Комментарий</th>

                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">E-MAIl</th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">Город</th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">Сумма сделки</th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">Посадочная</th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">Реферрер</th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">[UTM_TERM]</th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">[UTM_MEDIUM]</th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">[UTM_SOURCE]</th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">[UTM_CAMPAIGN]</th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">ИСТОЧНИК</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(lead) in stateLeads">
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <p class="text-sm font-weight-normal mb-0">{{ lead.number }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-center text-sm font-weight-normal mb-0">{{ lead.created_at }}</p>
                                </td>
                                <td :style="'background:' + ' ' + '#' + leadColor(lead.class)">
                                    <h6 class="text-center mb-0 font-weight-normal text-sm">{{  lead.name }}</h6>
                                </td>
                                <td class="text-white text-center">
                                    <div v-select class="select">
                                        <span class="select__title">{{ className(lead.class, 'Не задан') }}</span>
                                        <span class="material-icons select__arrow">expand_more</span>
                                        <div class="select__content">
                                            <div @click="colorDefault($event), getLeadClass(stateProject.id, lead.id, 0)" class="select__option">Не задан</div>
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
                                    <p class="mb-0 font-weight-normal text-sm">{{ lead.phone }}</p>
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
                                    {{ lead.utm?.utm_term }}
                                </td>
                                <td class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.utm?.utm_medium }}
                                </td>
                                <td class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.utm?.utm_source }}
                                </td>
                                <td class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.utm?.utm_campaign }}
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
    data () {
      return {
          counterDocListener: 0
      }
    },
    methods: {
        journalReverse (event) {
            this.$store.dispatch('journalReverse', event)
        },
        sortJournal (_param, _sortParam, _event) {
          this.$store.dispatch('sortJournal', { param: _param, sortParam: _sortParam, event: _event })
        },
        dropdownFilter ({
            event,
            coords: {
                left: _left = '',
                right: _right = '',
                top: _top = '',
                bottom: _bottom = ''
            } = {}
        }) {
            event.stopPropagation()
            event.stopImmediatePropagation()
            document.querySelectorAll('.select--active').forEach(item => {
                item.classList.remove('select--active')
                item.lastElementChild.style.maxHeight = '0px'
            })
            const deleteDiv = () => {
                document.querySelectorAll('.dropdown--active').forEach(item => {
                    item.classList.remove('dropdown--active')
                })
                this.counterDocListener = 0
                document.removeEventListener('click', deleteDiv)
            }
            const target = event.currentTarget
            target.style.position = 'relative'
            const div = target.lastElementChild
            const divBefore = div.lastElementChild
            div.style.left = _left + 'px'
            div.style.right = _right + 'px'
            div.style.top = _top + 'px'
            div.style.bottom = _bottom + 'px'
            divBefore.style.left = _left ? +_left + 35 + 'px' : ''
            if (div.classList.contains('dropdown--active')) {
                document.querySelectorAll('.dropdown--active').forEach(item => {
                    item.classList.remove('dropdown--active')
                })
            } else {
                document.querySelectorAll('.dropdown--active').forEach(item => {
                    item.classList.remove('dropdown--active')
                })
                div.classList.add('dropdown--active')
                if (this.counterDocListener === 0) {
                    this.counterDocListener++
                    document.addEventListener('click', deleteDiv)
                }
            }
        },
        async getLeadClass (projectId, leadId, classId) {
            const store = this.$store
            store.commit('switchSpinner')
            await axios.post(`/api/v1/project/${projectId}/journal/${leadId}/class/assign`, {
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
