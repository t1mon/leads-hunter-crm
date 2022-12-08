<template>
    <div v-if="stateLeads" class="journal">
        <div class="row journal__box">
            <div class="col-12 journal__wrap">
                <div class="card journal__card">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="journal__thead">
                            <tr>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">
                                    <span>#</span>
                                </th>

                                <th class="dropdown cursor-pointer text-center text-uppercase text-xxs font-weight-bolder">
                                    <p class="dropdown-toggle m-0 text-xxs font-weight-bolder opacity-10" id="filterDate" data-bs-toggle="dropdown" aria-expanded="false">Дата</p>
                                    <filter-app
                                        :ascDesc="{sort_by: 'created_at', sort_order: 'desc'}"
                                        class="dropdown-menu"
                                        aria-labelledby="filterDate"
                                    ></filter-app>
                                </th>
                                <th class="dropdown cursor-pointer text-center text-uppercase text-xxs font-weight-bolder">
                                    <p class="dropdown-toggle m-0 text-xxs font-weight-bolder opacity-10" id="filterName" data-bs-toggle="dropdown" aria-expanded="false">Клиент</p>
                                    <filter-app
                                                :ascDesc="{sort_by: 'name', sort_order: 'asc'}"
                                                :name="true"
                                                class="dropdown-menu"
                                                aria-labelledby="filterName"
                                    ></filter-app>
                                </th>
                                <th class="dropdown cursor-pointer text-center text-uppercase text-xxs font-weight-bolder">
                                    <p class="dropdown-toggle m-0 text-xxs font-weight-bolder opacity-10" id="filterClass" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">Класс</p>
                                    <filter-app
                                        :filterClass="true"
                                        class="dropdown-menu"
                                        aria-labelledby="filterClass"
                                    ></filter-app>
                                </th>
                                <th class="dropdown cursor-pointer text-center text-uppercase text-xxs font-weight-bolder">
                                    <p class="dropdown-toggle m-0 text-xxs font-weight-bolder opacity-10" id="filterPhone" data-bs-toggle="dropdown" aria-expanded="false">Телефон</p>
                                    <filter-app
                                        :ascDesc="{sort_by: 'phone', sort_order: 'asc'}"
                                        :filterPhone="true"
                                        class="dropdown-menu"
                                        aria-labelledby="filterPhone"
                                    ></filter-app>
                                </th>
                                <th class="dropdown cursor-pointer text-uppercase text-xxs font-weight-bolder">
                                    <p class="dropdown-toggle m-0 text-xxs font-weight-bolder opacity-10" id="filterEntries" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">№</p>
                                    <filter-app
                                        :ascDesc="{sort_by: 'entries', sort_order: 'asc'}"
                                        :filterEntries="true"
                                        class="dropdown-menu"
                                        aria-labelledby="filterEntries"
                                    ></filter-app>
                                </th>
                                <th class="cursor-pointer text-uppercase text-center text-xxs font-weight-bolder">Комментарий</th>

                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">E-MAIl</th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">Город</th>
                                <th class="text-center cursor-pointer text-uppercase text-xxs font-weight-bolder">Сумма сделки</th>
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
                            <tr v-for="(lead, index) in stateLeads">
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <p class="text-sm font-weight-normal mb-0 text-center">
                                                {{index + 1}}
                                            <hr class="m-0">
                                            id: {{ lead.id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-center text-sm font-weight-normal mb-0">{{ lead.created_at }}</p>
                                </td>
                                <td>
                                    <h6 class="text-center mb-0 font-weight-normal text-sm">{{  lead.name }}</h6>
                                </td>
                                <journal-classes :lead="lead"></journal-classes>
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
                                    {{ sumFormat(lead.cost) }}
                                </td>
                                <td @click="test()" class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.host }}
                                </td>
                                <td v-tLengthDyn="{text: lead.referrer, length: 25 }" class="text-sm font-weight-normal mb-0">
                                    <!--                                    {{ lead.referrer }}-->
                                </td>
                                <td class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.utm_term }}
                                </td>
                                <td class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.utm_medium }}
                                </td>
                                <td class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.utm_source }}
                                </td>
                                <td class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.utm_campaign }}
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
import Spinner from '../../Others/Spinner'
import FilterApp from '../filters/Filters'
import JournalClasses from "./JournalClasses";

export default {
    name: "Journal",
    components: {
        Spinner,
        FilterApp,
        JournalClasses
    },
    data () {
      return {
          counterDocListener: 0,
          first: false,
          second: false
      }
    },
    methods: {
        phoneFormat(phone) {
            if (!phone) return
            return phone.toString().replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, '+7 ($2) $3-$4')
        },
        sumFormat(sum) {
            if (!sum) return
            const sumStr = sum.toString()
            return sumStr.replace(/(\d{1,3}(?=(?:\d\d\d)+(?!\d)))/g, "$1" + ' ')
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
        getLeads (_projectId, _dateFrom, _dateTo, _rowsNum) {
            this.$store.dispatch('getLeads', { projectId: _projectId, dateFrom: _dateFrom, dateTo: _dateTo, rowsNum: _rowsNum })
        }
    },
    computed: {
        stateProjectId() {
            return this.$store.getters['journalAll/stateProjectId']
        },
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
.dropdown-toggle::after {
    display: none;
}
.dropdown-menu::before {
    color: #e91e63;
}
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
.table-responsive {
    padding-top: 50px;
    padding: 0 !important;
    overflow: auto !important;
    height: 100% !important;
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
