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
                        <input class="form-control p-0" id="from" type="date">
                    </div>
                    <div class="px-1 d-flex align-items-end border border-1 rounded-2">
                        <label class="m-0 me-2" for="to">По</label>
                        <input class="form-control p-0" id="to" type="date">
                    </div>
                </div>
                <button class="btn col-12 m-0 btn-primary py-1 rounded-2"> Применить </button>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "JournalPanelFilter",
    props: ['projectid'],
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
                            this.period = 'За всё время'
                            this.dateFrom = ''
                            this.dateTo = ''
                        }
                    },
                    {
                        text: 'Сегодня',
                        active: false,
                        getPeriod: () => {
                            const date = new Date().toLocaleString('ru', {
                                year: 'numeric',
                                month: 'numeric',
                                day: 'numeric'
                            })
                            console.log(date)
                            this.dateFrom = '2022.11.24'
                            this.dateTo = '2022.11.25'
                        }
                    },
                    {
                        text: 'Вчера',
                        active: false
                    },
                    {
                        text: 'Эта неделя',
                        active: false
                    },
                    {
                        text: 'Прошлая неделя',
                        active: false
                    }
                ],
                [
                    {
                        text: 'Этот месяц',
                        active: false
                    },
                    {
                        text: 'Прошлый месяц',
                        active: false
                    },
                    {
                        text: 'Этот квартал',
                        active: false
                    },
                    {
                        text: 'Прошлый квартал',
                        active: false
                    },
                    {
                        text: 'Этот год',
                        active: false
                    }
                ]
            ]
        }
    },
    methods: {
        async getPeriod(columnIndex, itemIndex) {
            this.filterColumns.forEach(col => {
                col.forEach(item => {
                    item.active = false
                })
            })
            const item = this.filterColumns[columnIndex][itemIndex]
            item.active = true
            await item.getPeriod()
            await this.$store.dispatch('journalAll/getJournalAll', { id: this.projectid, dateFrom: this.dateFrom, dateTo: this.dateTo })
        },
        allJournal () {
            localStorage.removeItem('dateFrom')
            localStorage.removeItem('dateTo')
            this.dateFrom = ''
            this.dateTo = ''
            this.$store.dispatch('journalAll/getJournalAll', this.projectid)
        }
    }
}
</script>

<style scoped>
.dropdown-menu::before {
    color: #e91e63
}
</style>
