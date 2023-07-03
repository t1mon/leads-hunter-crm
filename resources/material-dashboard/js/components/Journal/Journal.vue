<template>
    <journal-panel :columns="columns" @changeColumnsSettings="changeColumnsSettings"></journal-panel>
    <journal-list :columns="columns"></journal-list>
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
    data() {
      return {
          columns: {
              created_at: true,
              nextcall_date: true,
              name: true,
              classes: true,
              phone: true,
              entries: true,
              company: true,
              manual_region: true,
              comment_crm: true,
              email: true,
              city: true,
              cost: true,
              host: true,
              referrer: true,
              utm_term: true,
              utm_medium: true,
              utm_source: true,
              utm_campaign: true,
              source: true
          }
      }
    },
    methods: {
        changeColumnsSettings(column) {
            this.columns[column] = !this.columns[column]
        }
    },
    async created () {
        // const projectLS = localStorage.getItem('projects')
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
