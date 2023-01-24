<template>
    <td class="text-center">
        <p v-if="LeadsCount" class="text-xs font-weight-normal mb-0">{{ LeadsCount[project].totalLeads }}</p>
        <div v-if="!LeadsCount" class="spinner-border text-default m-auto" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </td>
    <td class="align-middle">
        <div class="d-flex align-items-center justify-content-center">
            <span v-if="LeadsCount" class="me-2 text-xs">{{ LeadsCount[project].leadsToday }}</span>
            <div v-if="!LeadsCount" class="spinner-border text-default m-auto" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </td>
</template>

<script>
export default {
    name: 'ProjectLeadsCount',
    data: () => ({
        count: null
    }),
    props: {
        project: {
            type: Number,
            requred: true
        },
    },
    computed: {
        LeadsCount () {
            return this.count
        },
    },

    methods: {
       async getLeadsCount (id) {
          await  axios
                .post('/api/v1/project/' + id + '/leads-count')
                .then(({ data }) => {
                    this.count = Object.assign({}, ...data.data.map(i => ({ [i.id]: { totalLeads: i.totalLeads, leadsToday: i.leadsToday } })))
                })
                .catch(() => {

                })
        },
    },

        mounted(){
            this.getLeadsCount(this.project)

        }
}
</script>

<style scoped>

</style>
