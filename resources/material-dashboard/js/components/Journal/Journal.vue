<template>
    <journal-panel></journal-panel>
    <journal-list></journal-list>
    <journal-paginate></journal-paginate>
</template>

<script>
import JournalPanel from './JournalPanel/JournalPanel'
import JournalList from './JournalList/JournalList'
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
        const projectIdLS = localStorage.getItem('projectId')
        if (!projectIdLS || projectIdLS != this.projectid) {
            this.$store.commit('filterParams/CLEAR_PARAMS')
            localStorage.setItem('projectId', this.projectid)
        }
        this.$store.commit('journalAll/SET_PROJECT_ID', this.projectid)
        await this.$store.dispatch('journalAll/getJournalAll')
    }
}
</script>

<style scoped>

</style>
