<template>
    <journal-panel :projectid="projectid"></journal-panel>
    <journal-list :projectid="projectid"></journal-list>
    <journal-paginate :projectid="projectid"></journal-paginate>
</template>

<script>
import JournalPanel from './JournalPanel/JournalPanel'
import JournalList from './JournalList'
import JournalPaginate from './JournalPaginate'

export default {
    props: ['projectid'],
    components: {
        JournalPanel,
        JournalList,
        JournalPaginate
    },
    name: "Journal",
    methods: {
        async getLeads (_projectId, _dateFrom, _dateTo) {
            await this.$store.dispatch('getLeads', { projectId: _projectId, dateFrom: _dateFrom, dateTo: _dateTo })
        }
    },
    async created () {
        const dateFrom = localStorage.getItem('dateFrom')
        const dateTo = localStorage.getItem('dateTo')
        // await this.getLeads(this.projectid, dateFrom, dateTo)
        await this.$store.dispatch('journalAll/getJournalAll', { id: this.projectid })
    }
}
</script>

<style scoped>

</style>
