<template>
    <div v-if="appliedFilters" @click="clearFilters" class="px-1 me-3 cursor-pointer border border-danger rounded-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Очистить фильтр">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#4CAF50" class="bi bi-funnel-fill" viewBox="0 0 16 16">
            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="#e91e63" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
        </svg>
    </div>
</template>

<script>
export default {
    name: "ClearFilters",
    computed: {
        appliedFilters() {
            const params = this.$store.getters['filterParams/stateParams']
            return params.date_from || params.sort_by || params.name || params.classes.length > 0 || params.phone || params.entries
        }
    },
    methods: {
        async clearFilters() {
            this.$store.commit('filterParams/CLEAR_PARAMS')
            await this.$store.dispatch('journalAll/getJournalAll')
        }
    }
}
</script>

<style scoped>

</style>
