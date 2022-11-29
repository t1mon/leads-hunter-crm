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
    async created () {
        await this.$store.dispatch('journalAll/getJournalAll', { id: this.projectid })
    }
}
</script>

<style scoped>

</style>
