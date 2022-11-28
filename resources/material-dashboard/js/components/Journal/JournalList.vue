<template>
    <div class="journal">
        <div class="row journal__box">
            <div class="col-12 journal__wrap">
                <div class="card journal__card">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="journal__thead">
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
                                            <span class="journal__filter__text" @click="sortJournal('number', 'sortNumber', $event)">По убыванию</span>
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
                                            <span class="journal__filter__text" @click="sortJournal('created_at_format', 'sortDate', $event)">По возрастанию</span>
                                        </div>
                                        <div class="journal__sort__before"></div>
                                    </div>
                                </th>
                                <th @click="dropdownFilter( {event: $event} )" class="cursor-pointer text-center text-uppercase text-xxs font-weight-bolder">
                                    <span>Клиент</span>
                                    <div class="journal__sort">
                                        <div class="journal__sort__content">
                                            <span class="journal__filter__text" @click="sortJournal('name', 'sortName', $event)">По возрастанию</span>
                                        </div>
                                        <div class="journal__sort__before"></div>
                                    </div>
                                </th>
                                <th class="cursor-pointer text-center text-uppercase text-xxs font-weight-bolder">Класс</th>
                                <th
                                    @click="dropdownFilter( {event: $event} )"
                                    class="cursor-pointer text-center text-uppercase text-xxs font-weight-bolder"
                                >
                                    <span>Телефон</span>
                                    <div class="journal__sort">
                                        <div class="journal__sort__content">
                                            <span class="journal__filter__text" @click="sortJournal('phone', 'sortPhone', $event)">По возрастанию</span>
                                        </div>
                                        <div class="journal__sort__before"></div>
                                    </div>
                                </th>
                                <th
                                    @click="dropdownFilter( {event: $event} )"
                                    class="cursor-pointer text-uppercase text-xxs font-weight-bolder"
                                >
                                    <span>№</span>
                                    <div class="journal__sort">
                                        <div class="journal__sort__content">
                                            <span class="journal__filter__text" @click="sortJournal('entries', 'sortEntries', $event)">По возрастанию</span>
                                            <label class="journal__sort__label">
                                                <input v-model="first" type="checkbox" class="journal__sort__input">
                                                <span class="material-icons journal__sort__ok">done</span>
                                                <span class="journal__sort__text">Первичное</span>
                                            </label>
                                            <label class="journal__sort__label">
                                                <input v-model="second" type="checkbox" class="journal__sort__input">
                                                <span class="material-icons journal__sort__ok">done</span>
                                                <span class="journal__sort__text">Вторичное</span>
                                            </label>
                                            <button @click="sortJournalEntries" class="journal__sort__button btn btn-primary">Отфильтровать</button>
                                        </div>
                                        <div class="journal__sort__before"></div>
                                    </div>
                                </th>
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
                            <tbody class="journal__tbody">
                            <tr v-for="(lead) in stateLeads">
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <p class="text-sm font-weight-normal mb-0">{{ lead.id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-center text-sm font-weight-normal mb-0">{{ lead.created_at }}</p>
                                </td>
                                <td>
                                    <h6 class="text-center mb-0 font-weight-normal text-sm">{{  lead.name }}</h6>
                                </td>
                                <td class="text-white text-center">
                                    <div v-select class="select">
                                        <span
                                            :style="'background: ' + '#' + leadColor(lead.class) + '; ' + (lead.class ? 'color: #ffffff' : '')"
                                            class="select__title">{{ className(lead.class, 'Не задан') }}</span>
                                        <span class="material-icons select__arrow">expand_more</span>
                                        <div class="select__content">
                                            <div @click="colorDefault($event), getLeadClass(stateProjectJour.id, lead.id, 0)" class="select__option">Не задан</div>
                                            <div v-for="projectClass in stateProjectJour.classes" @click="color($event, projectClass.color), getLeadClass(stateProjectJour.id, lead.id, projectClass.id)" class="select__option">
                                                <div class="journal__row">
                                                    <span class="journal__class-name">{{ projectClass.name }}</span>
                                                    <span :style="'background:' + ' ' + '#' + projectClass.color" class="journal__class-color"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <a :href="'tel: ' + lead.phone" class="mb-0 font-weight-normal text-sm">{{ phoneFormat(lead.phone) }}</a>
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
                                    <a v-if="lead.comment_crm" :href="'/project/project/' + stateProjectJour.id + '/' + lead.id + '/comment/' + lead.comment_crm.id" v-tLength="25">{{ lead.comment_crm.text }}</a>
                                    <a v-else :href="'/project/project/' + stateProjectJour.id + '/' + lead.id + '/comment/create'"><span class="material-icons">add</span></a>
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
                                <td @click="test()" class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.host }}
                                </td>
                                <td v-tLengthDyn="{text: lead.referrer, length: 25 }" class="text-sm font-weight-normal mb-0">
                                    <!--                                    {{ lead.referrer }}-->
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
          testtest: false,
          counterDocListener: 0,
          first: false,
          second: false
      }
    },
    methods: {
        phoneFormat(phone) {
            return phone.toString().replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, '+7 ($2) $3-$4')
        },
        test() {
          this.testtest = !this.testtest
            console.log(this.testtest)
        },
        sortJournalEntries () {
            const _dateFrom = this.$store.getters.stateDateFrom
            const _dateTo = this.$store.getters.stateDateTo
            let _entriesOperator = null
            if (this.first && !this.second) {
                _entriesOperator = '='
            } else if (!this.first && this.second) {
                _entriesOperator = '>'
            }
            this.$store.dispatch('getLeads', { projectId: this.projectid, entriesOperator: _entriesOperator, dateFrom: _dateFrom, dateTo: _dateTo })
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
            event.target.closest('td').firstChild.firstChild.style = ''
        },
        color (event, color) {
            event.target.closest('td').firstChild.firstChild.style.background = '#' + color
            event.target.closest('td').firstChild.firstChild.style.color = '#ffffff'
        },
        getLeads (_projectId, _dateFrom, _dateTo, _rowsNum) {
            this.$store.dispatch('getLeads', { projectId: _projectId, dateFrom: _dateFrom, dateTo: _dateTo, rowsNum: _rowsNum })
        }
    },
    computed: {
        stateIsLoadingJ () {
            return this.$store.getters.stateIsLoadingJ
        },
        stateLeads () {
            return this.$store.getters.stateLeads
        },
        stateProjectJour () {
            return this.$store.getters.stateProjectJour
        }
    }
}
</script>

<style scoped>
th {
    position: relative;
}
.journal {
    height: calc(100vh - 160px);
}
.table {
    position: relative;
}
.journal__thead {
    position: sticky;
    left: 0;
    top: 0;
    width: 100%;
    z-index: 200;
    background-color: #FCFCFE;
}
.dark-version .journal__thead {
    background-color: #202940;
}
.journal__thead::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 1px;
    left: 0;
    bottom: 0;
    background-color: #797F8D;
}
.journal__box {
    height: 100%;
}
.journal__card {
    height: 100%;
    overflow: hidden;
}
.journal__wrap {
    height: 100%;
}

.journal__sort__label {
    position: relative;
    cursor: pointer;
    display: block;
    text-align: left;
    transition: 0.3s;
    margin: 0;
    margin-bottom: 4px;
    padding: 4px;
    padding-left: 24px;
}
.journal__sort__label::before {
    content: '';
    position: absolute;
    left: 4px;
    top: 50%;
    width: 16px;
    height: 16px;
    transform: translateY(-50%);
    border: 1px solid #E91E63;
    border-radius: 2px;
}
.journal__sort__label:hover {
    background-color: #d0d0d0;
}
.journal__sort__button {
    width: 100%;
    margin-bottom: 0;
}
.journal__sort__input {
    display: none;
}
.journal__sort__ok {
    position: absolute;
    left: 4px;
    top: 50%;
    font-size: 16px;
    color: #000000;
    font-weight: 900;
    transform: translateY(-50%);
    display: none;
}
.journal__sort__text {
    color: #000000;
    font-size: 12px;
    text-transform: capitalize;
    font-weight: 500;
}
.journal__sort__input:checked + .journal__sort__ok {
    display: block;
}

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
    padding: 0 !important;
    overflow: auto !important;
    height: 100% !important;
}

.journal__filter__text {
    display: block;
    width: 100%;
    padding: 4px;
    transition: 0.3s;
}
.journal__filter__text:hover {
    background-color: #d0d0d0;
}
@media screen and (max-width: 991px) {
    .journal {
        height: calc(100vh - 205px);
    }
}
@media screen and (max-width: 767px) {
    .journal {
        height: calc(100vh - 310px);
    }
}
</style>
