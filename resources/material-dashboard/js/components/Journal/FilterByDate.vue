<template>

    <div class="row">
        <form @submit.prevent="getFilteredLeads(this.projectid, dateFrom, dateTo)" action="#" accept-charset="UTF-8" class="journal__date__form d-flex justify-content-between">
            <div class="journal__date__box">
                <div class="d-flex journal__date__dates">
                    <div class="input-group input-group-static my-3 p-2">
                        <label>Дата от</label>
                        <input v-model="dateFrom" type="date" class="form-control" name="date_from">
                    </div>
                    <div class="input-group input-group-static my-3 p-2">
                        <label> до</label>
                        <input v-model="dateTo" type="date" class="form-control" name="date_to">
                    </div>
                </div>
                <div class="journal__date__buttons">
                    <button type="submit" class="btn btn-primary journal__date__button"> Применить </button>
                    <button @click.prevent="getAllLeads" class="btn btn-primary journal__date__button"> Показать все </button>
                </div>
            </div>
            <div class="d-flex justify-content-end align-items-start">
                <button @click.prevent="exportJournal()" class="journal__date__button--last btn btn-primary" > Скачать записи </button>
            </div>
        </form>
    </div>

</template>

<script>
export default {
    name: "FilterByDate",
    props: ['projectid'],
    data () {
        return {
            dateFrom: '',
            dateTo: ''
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
        getAllLeads () {
            localStorage.removeItem('dateFrom')
            localStorage.removeItem('dateTo')
            this.dateFrom = ''
            this.dateTo = ''
            this.$store.dispatch('getLeads', { projectId: this.projectid })
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
    }
}
</script>

<style scoped>

.journal__date__dates {
    margin-right: 16px;
}

.journal__date__box {
    display: flex;
}

.journal__date__button {
    width: 100%;
}

.journal__date__buttons {
    display: flex;
    flex-direction: column;
}

@media screen and (max-width: 767px) {
    .journal__date__box {
        flex-direction: column;
    }
    .journal__date__buttons {
        flex-direction: row;
        justify-content: space-between;
    }
    .journal__date__button {
        margin-right: 16px;
        margin-bottom: 0;
    }
    .journal__date__button:last-of-type {
        margin-right: 0;
    }
    .journal__date__dates {
        margin-right: 0;
    }
    .journal__date__button--last {
        margin-top: 24px;
    }
}

@media screen and (max-width: 575px) {
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
