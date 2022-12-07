<template>
    <div
        @click="reverse"
         class="d-flex justify-content-center align-items-start text-xxs"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-arrow-down-up me-1" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z"/>
        </svg>
        <span v-if="stateParams.sort_by === sort_by">{{ stateParams.sort_order === 'asc' ? 'По убыванию' : 'По возрастанию' }}</span>
        <span v-else>{{ sort_order === 'asc' ? 'По возрастанию' : 'По убыванию' }}</span>
    </div>
    <hr class="my-1">
</template>

<script>
export default {
    name: "FilterAscDesc",
    props: {
        sort_by: {
            type: String,
            required: true
        },
        sort_order: {
            type: String,
            required: true
        }

    },
    data() {
        return {
            sortOrder: ''
        }
    },
    computed: {
        stateParams() {
            return this.$store.getters['filterParams/stateParams']
        }
    },
    methods: {
        async reverse() {
            this.$store.commit('filterParams/SET_SORT_BY', this.sort_by)
            this.$store.commit('filterParams/SET_SORT_ORDER', this.sortOrder)
            await this.$store.dispatch('journalAll/getJournalAll')
            this.sortOrder === 'asc' ? this.sortOrder = 'desc' : this.sortOrder = 'asc'
        }
    },
    created() {
        this.sortOrder = this.sort_order
        const sort_byLS = localStorage.getItem('sort_by')
        const sort_orderLS = localStorage.getItem('sort_order')
        if (sort_orderLS && sort_byLS && sort_byLS === this.sort_by) {
            this.$store.commit('filterParams/SET_SORT_BY', sort_byLS)
            this.$store.commit('filterParams/SET_SORT_ORDER', sort_orderLS)
            this.sortOrder = sort_orderLS
        } else {
            this.sortOrder = this.sort_order
        }
    }
}
</script>

<style scoped>

</style>
