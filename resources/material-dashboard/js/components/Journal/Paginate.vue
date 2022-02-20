<template>
    <div class="d-flex justify-content-center">
        <nav>
            <ul v-if="stateDataReady" class="pagination">
                <li @click="prevNext(this.projectid, { prev: stateProjectJour.leads.prev_page_url })" class="page-item paginate__arrow">
                    <span class="page-link">‹</span>
                </li>
                <li
                    v-for="link in getLinks()"
                    @click="getLeads(this.projectid, link.label, stateProjectJour.leads.path)"
                    class="page-item paginate__link"
                    :class="{ active : link.active }"
                >
                    <span class="page-link">{{ link.label }}</span>
                </li>
                <li @click="prevNext(this.projectid, { next: stateProjectJour.leads.next_page_url })" class="page-item paginate__arrow">
                    <span class="page-link">›</span>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    name: "Paginate",
    props: ['projectid'],
    methods: {
        getLeads (_projectId, _paginateNum, _paginatePath) {
            this.$store.dispatch('getLeads', { projectId: _projectId, paginateNum: _paginateNum, paginatePath: _paginatePath })
        },
        getLinks () {
            const links = this.stateProjectJour.leads.links.slice()
            links.pop()
            links.shift()
            return links
        },
        prevNext (_projectId, { prev: _prev, next: _next }) {
            let _prevNext
            if (_prev || _next) {
                _prevNext = _prev || _next
            } else {
                return
            }
            this.$store.dispatch('getLeads', { projectId: _projectId, prevNext: _prevNext })
        }
    },
    computed: {
        stateProjectJour () {
            return this.$store.getters.stateProjectJour
        },
        stateDataReady () {
            return this.$store.getters.stateDataReady
        }
    }
}
</script>

<style scoped>
.paginate__arrow {
    cursor: pointer;
}
.paginate__link {
    cursor: pointer;
}

</style>
