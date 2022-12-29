<template>
    <td
        @click="setData()"
        class="cursor-pointer text-sm text-center font-weight-normal mb-0 overflow-hidden"
        style="width: 150px; min-width: 150px; max-width: 150px; text-overflow: ellipsis"
        data-bs-toggle="modal" data-bs-target="#journalCompany"
    >
        <span
            v-if="company"
            :title="company"
        >{{ company }}</span>
        <span v-else>
        <span class="material-icons">add</span>
        </span>
    </td>
</template>

<script>
export default {
    name: "JournalCompany",
    props: {
        companyBack: {},
        leadId: {
            required: true
        }
    },
    data() {
        return {
            company: ''
        }
    },
    watch: {
        stateCompany(company) {
            if (this.leadId === this.stateLeadId) this.company = company
        }
    },
    computed: {
        stateCompany() {
            return this.$store.getters['journalCompany/stateCompany']
        },
        stateLeadId() {
            return this.$store.getters['journalCompany/stateLeadId']
        }
    },
    methods: {
        setData() {
            this.$parent.$refs.journalCompanyModal.company = this.company
            this.$store.commit('journalCompany/SET_COMPANY', this.company)
            this.$store.commit('journalCompany/SET_LEAD_ID', this.leadId)
        }
    },
    created() {
        this.company = this.companyBack
    }
}
</script>

<style scoped>

</style>
