<template>
    <div class="dropdown border border-1 rounded-2 border-primary">

        <p class="m-0 cursor-pointer px-1 text-sm dropdown-toggle" id="filterDate" data-bs-toggle="dropdown" aria-expanded="false">
            {{ period }}
        </p>

        <div class="dropdown-menu p-2 border border-1 rounded-2 border-primary" aria-labelledby="filterDate">

            <div class="d-flex gap-2 mb-2">
                <div
                    v-for="(column, columnIndex) in filterColumns"
                    class="d-flex flex-column gap-2 w-50">
                    <p
                        v-for="(item, itemIndex) in column"
                        @click="getPeriod(columnIndex, itemIndex)"
                        :class="{'bg-success' : item.active}"
                        class="text-sm m-0 bg-secondary text-white text-nowrap px-2 py-1 rounded-2">{{ item.text }}</p>
                </div>
            </div>

            <div class="m-0 text-nowrap px-2 py-1 border border-1 rounded-2 border-primary">
                <p class="text-sm mb-1 text-center fw-normal">ПЕРИОД</p>

                <div class="d-flex flex-column flex-sm-row gap-2 mb-2">
                    <div class="px-1 d-flex align-items-end border border-1 rounded-2">
                        <label class="m-0 me-2" for="from">C</label>
                        <input v-model="dateFrom" class="form-control p-0" id="from" type="date">
                    </div>
                    <div class="px-1 d-flex align-items-end border border-1 rounded-2">
                        <label class="m-0 me-2" for="to">По</label>
                        <input v-model="dateTo" class="form-control p-0" id="to" type="date">
                    </div>
                </div>
                <button @click="getPeriod()" class="btn col-12 m-0 btn-primary py-1 rounded-2"> Применить </button>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "JournalPanelFilter",
    data() {
        return {
            dateFrom: '',
            dateTo: '',
            period: 'За всё время',
            filterColumns: [
                [
                    {
                        text: 'За всё время',
                        active: true,
                        getPeriod: () => {
                            this.dateFrom = ''
                            this.dateTo = ''
                            this.period = 'За всё время'
                        }
                    },
                    {
                        text: 'Сегодня',
                        active: false,
                        getPeriod: () => {
                            const today = new Date()
                            this.dateFrom = this.dateFormatReq(today)
                            this.dateTo = this.dateFormatReq(today)
                            this.period = this.dateFormat(today)
                        }
                    },
                    {
                        text: 'Вчера',
                        active: false,
                        getPeriod: () => {
                            const today = new Date()
                            const yesterday = new Date(today - 86400000)
                            this.dateFrom = this.dateFormatReq(yesterday)
                            this.dateTo = this.dateFormatReq(yesterday)
                            this.period = this.dateFormat(yesterday)
                        }
                    },
                    {
                        text: 'Эта неделя',
                        active: false,
                        getPeriod: () => {
                            const today = new Date()
                            const day = today.getDay() === 0 ? 7 : today.getDay()
                            const beginWeek = new Date(today - (86400000 * (day - 1)))
                            this.dateFrom = this.dateFormatReq(beginWeek)
                            this.dateTo = this.dateFormatReq(today)
                            this.period = `${this.dateFormat(beginWeek)} - ${this.dateFormat(today)}`
                        }
                    },
                    {
                        text: 'Прошлая неделя',
                        active: false,
                        getPeriod: () => {
                            const today = new Date()
                            const day = today.getDay() === 0 ? 7 : today.getDay()
                            const beginWeek = new Date(today - (86400000 * (day - 1)))
                            const beginWeekLast = new Date(beginWeek - (86400000 * 7))
                            const endWeekLast = new Date(beginWeek - 86400000)
                            this.dateFrom = this.dateFormatReq(beginWeekLast)
                            this.dateTo = this.dateFormatReq(endWeekLast)
                            this.period = `${this.dateFormat(beginWeekLast)} - ${this.dateFormat(endWeekLast)}`
                        }
                    }
                ],
                [
                    {
                        text: 'Этот месяц',
                        active: false,
                        getPeriod: () => {
                            const today = new Date()
                            const day = today.getDate()
                            const beginMonth = new Date(today - (86400000 * (day - 1)))
                            this.dateFrom = this.dateFormatReq(beginMonth)
                            this.dateTo = this.dateFormatReq(today)
                            this.period = `${this.dateFormat(beginMonth)} - ${this.dateFormat(today)}`
                        }
                    },
                    {
                        text: 'Прошлый месяц',
                        active: false,
                        getPeriod: () => {
                            const today = new Date()
                            const day = today.getDate()
                            const endMonthLast = new Date(today - (86400000 * day))
                            const dayMonthLast = endMonthLast.getDate()
                            const beginMonthLast = new Date(endMonthLast - (86400000 * (dayMonthLast - 1)))
                            this.dateFrom = this.dateFormatReq(beginMonthLast)
                            this.dateTo = this.dateFormatReq(endMonthLast)
                            this.period = `${this.dateFormat(beginMonthLast)} - ${this.dateFormat(endMonthLast)}`
                        }
                    },
                    {
                        text: 'Этот квартал',
                        active: false,
                        getPeriod: () => {
                            const today = new Date()
                            let beginQuarter
                            let endQuarter
                            switch (today.getMonth()) {
                                case 0:
                                case 1:
                                case 2:
                                    beginQuarter = new Date(today.getFullYear(), 0)
                                    endQuarter = new Date(today.getFullYear(), 2, 31)
                                    break
                                case 3:
                                case 4:
                                case 5:
                                    beginQuarter = new Date(today.getFullYear(), 3)
                                    endQuarter = new Date(today.getFullYear(), 5, 30)
                                    break
                                case 6:
                                case 7:
                                case 8:
                                    beginQuarter = new Date(today.getFullYear(), 6)
                                    endQuarter = new Date(today.getFullYear(), 8, 30)
                                    break
                                case 9:
                                case 10:
                                case 11:
                                    beginQuarter = new Date(today.getFullYear(), 9)
                                    endQuarter = new Date(today.getFullYear(), 11, 31)
                                    break
                            }
                            this.dateFrom = this.dateFormatReq(beginQuarter)
                            this.dateTo = this.dateFormatReq(endQuarter)
                            this.period = `${this.dateFormat(beginQuarter)} - ${this.dateFormat(endQuarter)}`
                        }
                    },
                    {
                        text: 'Прошлый квартал',
                        active: false,
                        getPeriod: () => {
                            const today = new Date()
                            let beginQuarter
                            let endQuarter
                            switch (today.getMonth()) {
                                case 0:
                                case 1:
                                case 2:
                                    beginQuarter = new Date(today.getFullYear() - 1, 9)
                                    endQuarter = new Date(today.getFullYear() - 1, 11, 31)
                                    break
                                case 3:
                                case 4:
                                case 5:
                                    beginQuarter = new Date(today.getFullYear(), 0)
                                    endQuarter = new Date(today.getFullYear(), 2, 31)
                                    break
                                case 6:
                                case 7:
                                case 8:
                                    beginQuarter = new Date(today.getFullYear(), 3)
                                    endQuarter = new Date(today.getFullYear(), 5, 30)
                                    break
                                case 9:
                                case 10:
                                case 11:
                                    beginQuarter = new Date(today.getFullYear(), 6)
                                    endQuarter = new Date(today.getFullYear(), 8, 30)
                                    break
                            }
                            this.dateFrom = this.dateFormatReq(beginQuarter)
                            this.dateTo = this.dateFormatReq(endQuarter)
                            this.period = `${this.dateFormat(beginQuarter)} - ${this.dateFormat(endQuarter)}`
                        }
                    },
                    {
                        text: 'Этот год',
                        active: false,
                        getPeriod: () => {
                            const today = new Date()
                            const currentYear = today.getFullYear()
                            const firstDayCurrYear = new Date(currentYear, 0, 1)
                            this.dateFrom = this.dateFormatReq(firstDayCurrYear)
                            this.dateTo = this.dateFormatReq(today)
                            this.period = `${this.dateFormat(firstDayCurrYear)} - ${this.dateFormat(today)}`
                        }
                    }
                ]
            ]
        }
    },
    watch: {
        stateParamsDateFrom(dateFrom) {
            if (!dateFrom) {
                this.dateFrom = ''
                this.dateTo = ''
                this.period = 'За всё время'
                this.filterColumns.forEach(col => {
                    col.forEach(item => {
                        item.active = false
                    })
                })
                this.filterColumns[0][0].active = true
            }
        }
    },
    computed: {
        stateParamsDateFrom() {
            return this.$store.getters['filterParams/stateParamsDateFrom']
        },
        stateParamsDateTo() {
            return this.$store.getters['filterParams/stateParamsDateTo']
        }
    },
    methods: {
        addZero(num) {
            const _num = num < 10 ? `0${num}` : num
            return _num
        },
        dateFormat(date) {
            return date.toLocaleString('ru', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            })
        },
        dateFormatReq(date) {
            return `${date.getFullYear()}-${this.addZero(date.getMonth() + 1)}-${this.addZero(date.getDate())}`
        },
        async getPeriod(columnIndex, itemIndex) {
            this.filterColumns.forEach(col => {
                col.forEach(item => {
                    item.active = false
                })
            })
            if(typeof columnIndex === 'number') {
                const item = this.filterColumns[columnIndex][itemIndex]
                item.active = true
                await item.getPeriod()
                localStorage.removeItem('date_from')
                localStorage.removeItem('date_to')
                localStorage.setItem('columnIndex', columnIndex)
                localStorage.setItem('itemIndex', itemIndex)
            } else {
                if(!this.dateFrom) {
                    return
                }
                this.period = `${this.dateFormat(new Date(this.dateFrom))} - ${this.dateFormat(new Date(this.dateTo))}`
                localStorage.removeItem('columnIndex')
                localStorage.removeItem('itemIndex')
                localStorage.setItem('date_from', this.dateFrom)
                localStorage.setItem('date_to', this.dateTo)
            }
            this.$store.commit('filterParams/SET_DATE_FROM', this.dateFrom)
            this.$store.commit('filterParams/SET_DATE_TO', this.dateTo)

            await this.$store.dispatch('journalAll/getJournalAll')
        }
    },
    async created() {
        const columnIndexLS = localStorage.getItem('columnIndex')
        const itemIndexLS = localStorage.getItem('itemIndex')
        const date_fromLS = localStorage.getItem('date_from')
        const date_toLS = localStorage.getItem('date_to')
        if (columnIndexLS && itemIndexLS) {
            await this.getPeriod(+columnIndexLS, +itemIndexLS)
        }
        if (date_fromLS && date_toLS) {
            this.dateFrom = date_fromLS
            this.dateTo = date_toLS
            await this.getPeriod()
        }
    }
}
</script>

<style scoped>
.dropdown-menu::before {
    color: #e91e63
}
</style>
