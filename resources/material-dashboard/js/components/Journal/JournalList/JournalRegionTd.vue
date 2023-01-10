<template>
    <td
        @click="setData()"
        class="cursor-pointer text-sm text-center font-weight-normal mb-0 overflow-hidden"
        style="width: 150px; min-width: 150px; max-width: 150px; text-overflow: ellipsis"
        data-bs-toggle="modal" data-bs-target="#journalRegion"
    >
        <span
            v-if="region"
            :title="region"
        >{{ region }}</span>
        <span v-else>
        <span class="material-icons">add</span>
        </span>
    </td>
</template>

<script>
export default {
    name: "JournalRegionTd",
    props: {
        manualRegion: {},
        leadId: {
            required: true
        }
    },
    data() {
        return {
            region: ''
        }
    },
    watch: {
        stateManualRegion(region) {
            if (this.leadId === this.stateLeadId) this.region = region
        }
    },
    computed: {
        stateManualRegion() {
            return this.$store.getters['journalRegion/stateManualRegion']
        },
        stateLeadId() {
            return this.$store.getters['journalRegion/stateLeadId']
        }
    },
    methods: {
        setData() {
            this.$parent.$refs.journalRegionModal.region = this.region
            this.$store.commit('journalRegion/SET_MANUAL_REGION', this.region)
            this.$store.commit('journalRegion/SET_LEAD_ID', this.leadId)
        }
    },
    created() {
        this.region = this.manualRegion
    }
}
</script>

<style scoped>

</style>
