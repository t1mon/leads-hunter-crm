<template>
    <journal-panel></journal-panel>
    <journal-list :projectId="projectid"></journal-list>
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

        //функционал по изменению размеров колонок
        // const resizes = document.querySelectorAll('.journal__col-resize')
        // const table = document.querySelector('.journal__wrap')
        // // const journalThHeaders = document.querySelectorAll('.journal__th__header')
        // let startPoint = 0
        //
        // const moving = (eMousemove) => {
        //     eMousemove.preventDefault()
        //     console.log(eMousemove.pageX - startPoint)
        // }
        //
        // resizes.forEach(el => {
        //     el.addEventListener('mousedown', (eMousedown) => {
        //         eMousedown.preventDefault()
        //         startPoint = eMousedown.pageX
        //         table.addEventListener('mousemove', moving)
        //     })
        // })
        // table.addEventListener('mouseup', (eMouseup) => {
        //     eMouseup.preventDefault()
        //     table.removeEventListener('mousemove', moving)
        // })
        // table.addEventListener('mouseout', (eMouseout) => {
        //     console.log('hello')
        //     table.removeEventListener('mousemove', moving)
        // })
    }
}
</script>

<style scoped>

</style>
