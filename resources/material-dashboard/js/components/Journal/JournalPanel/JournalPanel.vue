<template>

    <div v-if="stateProjectJour" class="row filter-by-date">
        <div class="d-flex justify-content-between flex-column flex-sm-row align-items-sm-end">
            <div class="journal__date__box align-items-start align-items-lg-end flex-column flex-lg-row mb-2 mb-sm-0">
                <h5 class="m-0 me-3">{{ stateProjectJour.name }}</h5>

                <journal-panel-filter :projectid="projectid"></journal-panel-filter>
            </div>

            <div class="d-flex justify-content-between justify-content-sm-end">
                <clear-filters></clear-filters>
                <button @click.prevent="exportJournal()" class="journal__date__button--last btn btn-primary mb-0 py-1 px-3" > Скачать записи </button>
            </div>
        </div>
    </div>

</template>

<script>
import JournalPanelFilter from "./JournalPanelFilter";
import ClearFilters from "./ClearFilters";

export default {
    name: "JournalPanel",
    components: {
        JournalPanelFilter,
        ClearFilters
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
    methods: {
        getFilteredLeads (_projectId, _dateFrom, _dateTo) {
            this.$store.dispatch('getLeads', { projectId: _projectId, dateFrom: _dateFrom, dateTo: _dateTo })
            localStorage.setItem('dateFrom', _dateFrom)
            localStorage.setItem('dateTo', _dateTo)
        },
        exportJournal () {
            const query = {
                date_from: this.$store.getters['filterParams/stateParams'].date_from,
                date_to: this.$store.getters['filterParams/stateParams'].date_to
            }
            const url = window.location.href + `/download?` + $.param(query)
            window.location = url
        }
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
    .input-group {
        max-width: 50%;
    }
    .journal__date__button--last {
        margin: 0;
    }
}
</style>
