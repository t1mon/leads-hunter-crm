<template>
    <div v-if="stateLeads" class="journal">
        <div class="row journal__box">
            <div class="col-12 journal__wrap">
                <div class="card journal__card">
                    <div class="table-responsive">
                        <table class="journal__table table align-items-center mb-0">
                            <thead class="journal__thead">
                            <tr>
                                <th class="journal__th__header cursor-pointer text-uppercase text-xxs font-weight-bolder">
                                    <span>#</span>
                                    <div class="journal__col-resize"></div>
                                </th>

                                <th class="dropdown cursor-pointer text-center text-uppercase text-xxs font-weight-bolder">
                                    <p class="journal__th__header dropdown-toggle m-0 text-xxs font-weight-bolder opacity-10" id="filterDate" data-bs-toggle="dropdown" aria-expanded="false">Дата</p>
                                    <filter-app
                                        :ascDesc="{sort_by: 'created_at', sort_order: 'desc'}"
                                        class="dropdown-menu"
                                        aria-labelledby="filterDate"
                                    ></filter-app>
                                    <div class="journal__col-resize"></div>
                                </th>
                                <th class="cursor-pointer text-center text-uppercase text-xxs font-weight-bolder">
                                    <p class="journal__th__header dropdown-toggle m-0 text-xxs font-weight-bolder opacity-10">Дата следующего <br> звонка</p>
                                    <div class="journal__col-resize"></div>
                                </th>
                                <th class="dropdown cursor-pointer text-center text-uppercase text-xxs font-weight-bolder">
                                    <p class="journal__th__header dropdown-toggle m-0 text-xxs font-weight-bolder opacity-10" id="filterName" data-bs-toggle="dropdown" aria-expanded="false">Клиент</p>
                                    <filter-app
                                                :ascDesc="{sort_by: 'name', sort_order: 'asc'}"
                                                :name="true"
                                                class="dropdown-menu"
                                                aria-labelledby="filterName"
                                    ></filter-app>
                                    <div class="journal__col-resize"></div>
                                </th>
                                <th class="dropdown cursor-pointer text-center text-uppercase text-xxs font-weight-bolder">
                                    <p class="journal__th__header dropdown-toggle m-0 text-xxs font-weight-bolder opacity-10" id="filterClass" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">Класс</p>
                                    <filter-app
                                        :filterClass="true"
                                        class="dropdown-menu"
                                        aria-labelledby="filterClass"
                                    ></filter-app>
                                    <div class="journal__col-resize"></div>
                                </th>
                                <th class="dropdown cursor-pointer text-center text-uppercase text-xxs font-weight-bolder">
                                    <p class="journal__th__header dropdown-toggle m-0 text-xxs font-weight-bolder opacity-10" id="filterPhone" data-bs-toggle="dropdown" aria-expanded="false">Телефон</p>
                                    <filter-app
                                        :ascDesc="{sort_by: 'phone', sort_order: 'asc'}"
                                        :filterPhone="true"
                                        class="dropdown-menu"
                                        aria-labelledby="filterPhone"
                                    ></filter-app>
                                    <div class="journal__col-resize"></div>
                                </th>
                                <th class="dropdown cursor-pointer text-uppercase text-xxs font-weight-bolder">
                                    <p class="journal__th__header dropdown-toggle m-0 text-xxs font-weight-bolder opacity-10" id="filterEntries" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">№</p>
                                    <filter-app
                                        :ascDesc="{sort_by: 'entries', sort_order: 'asc'}"
                                        :filterEntries="true"
                                        class="dropdown-menu"
                                        aria-labelledby="filterEntries"
                                    ></filter-app>
                                    <div class="journal__col-resize"></div>
                                </th>
                                <th class="cursor-pointer text-uppercase text-center text-xxs font-weight-bolder">
                                    <p class="journal__th__header m-0 text-xxs font-weight-bolder opacity-10">Комментарий</p>
                                    <div class="journal__col-resize"></div>
                                </th>

                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">
                                    <p class="journal__th__header m-0 text-xxs font-weight-bolder opacity-10">E-MAIl</p>
                                    <div class="journal__col-resize"></div>
                                </th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">
                                    <p class="journal__th__header m-0 text-xxs font-weight-bolder opacity-10">Город</p>
                                    <div class="journal__col-resize"></div>
                                </th>
                                <th class="text-center cursor-pointer text-uppercase text-xxs font-weight-bolder">
                                    <p class="journal__th__header m-0 text-xxs font-weight-bolder opacity-10">Сумма сделки</p>
                                    <div class="journal__col-resize"></div>
                                </th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">
                                    <p class="journal__th__header m-0 text-xxs font-weight-bolder opacity-10">Посадочная</p>
                                    <div class="journal__col-resize"></div>
                                </th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">
                                    <p class="journal__th__header m-0 text-xxs font-weight-bolder opacity-10">Реферрер</p>
                                    <div class="journal__col-resize"></div>
                                </th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">
                                    <p class="journal__th__header m-0 text-xxs font-weight-bolder opacity-10">[UTM_TERM]</p>
                                    <div class="journal__col-resize"></div>
                                </th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">
                                    <p class="journal__th__header m-0 text-xxs font-weight-bolder opacity-10">[UTM_MEDIUM]</p>
                                    <div class="journal__col-resize"></div>
                                </th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">
                                    <p class="journal__th__header m-0 text-xxs font-weight-bolder opacity-10">[UTM_SOURCE]</p>
                                    <div class="journal__col-resize"></div>
                                </th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">
                                    <p class="journal__th__header m-0 text-xxs font-weight-bolder opacity-10">[UTM_CAMPAIGN]</p>
                                    <div class="journal__col-resize"></div>
                                </th>
                                <th class="cursor-pointer text-uppercase text-xxs font-weight-bolder">
                                    <p class="journal__th__header m-0 text-xxs font-weight-bolder opacity-10">ИСТОЧНИК</p>
                                    <div class="journal__col-resize"></div>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="journal__tbody">
                            <tr v-for="(lead, index) in stateLeads">
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <p class="text-sm font-weight-normal mb-0 text-center">
                                                {{(index + 1) + (stateProjectJour.leads.meta.per_page * (stateProjectJour.leads.meta.current_page - 1))}}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p v-html="dateFormat(lead.created_at)" class="text-center text-sm font-weight-normal mb-0"></p>
                                </td>
                                <td class="p-2">
                                    <call-back-date></call-back-date>
                                </td>
                                <td style="text-overflow: ellipsis; width: 100px; max-width: 100px">
                                    <h6 class="text-center mb-0 font-weight-normal text-sm" style="text-overflow: ellipsis; overflow: hidden">{{  lead.name }}</h6>
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

                                <td
                                    @click="comments(lead.comment_crm, lead.id)"
                                    class="align-middle text-center text-sm overflow-hidden cursor-pointer"
                                    style="width: 200px; min-width: 200px; max-width: 200px; text-overflow: ellipsis"
                                    data-bs-toggle="modal" data-bs-target="#journalComments"
                                >
                                    <span
                                        v-if="lead.comment_crm"
                                        :title="lead.comment_crm.text"
                                    >{{ lead.comment_crm.text }}</span>
                                    <span v-else>
                                        <span class="material-icons">add</span>
                                    </span>
                                </td>

                                <td
                                    class="text-sm text-center font-weight-normal mb-0 overflow-hidden"
                                    style="width: 150px; min-width: 150px; max-width: 150px; text-overflow: ellipsis"
                                >
                                    <span :title="lead.email">{{ lead.email }}</span>
                                </td>
                                <td
                                    class="text-sm text-center font-weight-normal mb-0 overflow-hidden"
                                    style="width: 150px; min-width: 150px; max-width: 150px; text-overflow: ellipsis"
                                >
                                    <span :title="lead.city">{{ lead.city }}</span>
                                </td>
                                <td class="text-sm text-center font-weight-normal mb-0">
                                    {{ sumFormat(lead.cost) }}
                                </td>
                                <td class="text-sm text-center font-weight-normal mb-0">
                                    {{ lead.host }}
                                </td>
                                <td
                                    class="text-sm font-weight-normal mb-0 overflow-hidden"
                                    style="width: 150px; min-width: 150px; max-width: 150px; text-overflow: ellipsis"
                                >
                                    <span :title="lead.referrer">{{ lead.referrer }}</span>
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
                                <td
                                    class="text-sm text-center font-weight-normal mb-0 overflow-hidden"
                                    style="width: 150px; min-width: 150px; max-width: 150px; text-overflow: ellipsis"
                                >
                                    <span :title="lead.utm_campaign">{{ lead.utm_campaign }}</span>
                                </td>
                                <td
                                    class="text-sm font-weight-normal mb-0 overflow-hidden"
                                    style="width: 150px; min-width: 150px; max-width: 150px; text-overflow: ellipsis"
                                >
                                    <span :title="lead.source">{{ lead.source }}</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <journal-comments></journal-comments>
    </div>
</template>

<script>
import Spinner from '../../Others/Spinner'
import FilterApp from '../filters/Filters'
import JournalClasses from "./JournalClasses";
import JournalComments from "./JournalComments";
import CallBackDate from "./CallBackDate";

export default {
    name: "Journal",
    components: {
        Spinner,
        FilterApp,
        JournalClasses,
        JournalComments,
        CallBackDate
    },
    data () {
      return {
          counterDocListener: 0,
          first: false,
          second: false
      }
    },
    methods: {
        async comments(comment_crm, leadId) {
            this.$store.commit('journalComments/CLEAR_COMMENT')
            if (comment_crm) {
                await this.$store.dispatch('journalComments/commentShow', comment_crm.id)
                this.$store.commit('journalComments/SET_COMMENT_ID', comment_crm.id)
            }
            this.$store.commit('journalComments/SET_LEAD_ID', leadId)
        },
        dateFormat(date) {
            const arrStr = date.split(' ')
            const dateFormat = arrStr[0] + '<br>' + arrStr[1]
            return dateFormat
        },
        phoneFormat(phone) {
            if (!phone) return
            return phone.toString().replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, '+7 ($2) $3-$4')
        },
        sumFormat(sum) {
            if (!sum) return
            const sumStr = sum.toString()
            return sumStr.replace(/(\d{1,3}(?=(?:\d\d\d)+(?!\d)))/g, "$1" + ' ')
        }
    },
    computed: {
        stateProjectId() {
            return this.$store.getters['journalAll/stateProjectId']
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
th, td {
    border-width: 1px;
}
.journal {
    height: calc(100vh - 160px);
}
.journal__col-resize {
    position: absolute;
    z-index: 2;
    width: 8px;
    height: 100%;
    top: 0;
    right: -4px;
    cursor: col-resize;
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
