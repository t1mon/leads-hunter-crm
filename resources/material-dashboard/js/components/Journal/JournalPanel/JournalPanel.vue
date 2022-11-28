<template>

    <div v-if="stateProjectJour" class="row filter-by-date">
        <form @submit.prevent="getFilteredLeads(this.projectid, dateFrom, dateTo)" action="#" accept-charset="UTF-8" class="journal__date__form d-flex justify-content-between">
            <div class="journal__date__box align-items-start align-items-lg-end flex-column flex-lg-row">
                <h5 class="m-0 me-3">{{ stateProjectJour.name }}</h5>

                <journal-panel-filter :projectid="projectid"></journal-panel-filter>
            </div>
            <div class="d-flex justify-content-end align-items-end">
                <button @click.prevent="exportJournal()" class="journal__date__button--last btn btn-primary mb-0 py-1 px-3" > Скачать записи </button>
            </div>
        </form>
    </div>

</template>

<script>
import JournalPanelFilter from "./JournalPanelFilter";

export default {
    name: "JournalPanel",
    components: {
        JournalPanelFilter
    },
    props: ['projectid'],
    data () {
        return {
        }
    },
    computed: {
        stateProjectJour () {
            return this.$store.getters.stateProjectJour
        }
    },
    watch: {
        dateFrom (e) {
            this.$store.dispatch('setDateFromTo', { dateFrom: e })
        },
        dateTo (e) {
            this.$store.dispatch('setDateFromTo', { dateTo: e })
        }
    },
    methods: {
        getDateFromLS () {
            const dateFrom = localStorage.getItem('dateFrom')
            const dateTo = localStorage.getItem('dateTo')
            if (dateFrom) {
                this.dateFrom = dateFrom
            }
            if (dateTo) {
                this.dateTo = dateTo
            }
        },
        getFilteredLeads (_projectId, _dateFrom, _dateTo) {
            this.$store.dispatch('getLeads', { projectId: _projectId, dateFrom: _dateFrom, dateTo: _dateTo })
            localStorage.setItem('dateFrom', _dateFrom)
            localStorage.setItem('dateTo', _dateTo)
        },
        exportJournal () {
            const query = {
                date_from: this.dateFrom,
                date_to: this.dateTo
            }
            const url = window.location.href + `/download?` + $.param(query)
            window.location = url
        }
    },
    created () {
        this.getDateFromLS()

        const date = new Date()
        const currentYear = date.getFullYear()
        const firstDayCurrYear = new Date(currentYear, 0, 1)
        const dateFrom = firstDayCurrYear.toLocaleString('ru', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        })
        console.log(dateFrom)
        const dateTo = date.toLocaleString('ru', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        })
        this.dateFrom = dateFrom
        this.dateTo = dateTo
    }
}
</script>

<style scoped>
.filter-by-date {
    margin-bottom: 5px;
}
.form-control {
    padding: 0 !important;
}

.journal__date__dates {
    margin-right: 16px;
}

.journal__date__box {
    display: flex;
}

.journal__date__button {
}
.journal__date__buttons {
    display: flex;
    align-items: flex-end;
}
.journal__date__button--first {
    margin-right: 16px;
}

@media screen and (max-width: 991px) {
    .journal__date__dates {
        margin-right: 0;
        margin-bottom: 5px;
    }
    .journal__date__button {
        width: 100%;
    }
    .journal__date__buttons {
        padding: 0 4px;
    }
}
@media screen and (max-width: 767px) {
    .journal__date__form {
        flex-direction: column-reverse;
    }
    .input-group {
        max-width: 50%;
    }
    .journal__date__button--last {
        margin: 0;
    }
}
</style>
