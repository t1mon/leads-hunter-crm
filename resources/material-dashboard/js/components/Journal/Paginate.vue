<template>
    <div v-if="stateDataReady" class="journal__paginate__row">

        <h6 class="journal__paginate__total">Всего записей: {{ stateProjectJour.leads.total }}</h6>

        <div
            v-if="getLinks.length > 1"
            class="journal__paginate__box"
        >
            <nav>
                <ul class="pagination">
                    <li
                        class="page-item paginate__arrow"
                        @click="prevNext(this.projectid, { prev: stateProjectJour.leads.prev_page_url })"
                    >
                        <span class="page-link">‹</span>
                    </li>
                    <li
                        v-for="link in getLinks"
                        class="page-item paginate__link"
                        :class="{ active : link.active }"
                        @click="getLeads(this.projectid, link.label, stateProjectJour.leads.path, link.url)"
                    >
                        <span class="page-link">{{ link.label }}</span>
                    </li>
                    <li
                        class="page-item paginate__arrow"
                        @click="prevNext(this.projectid, { next: stateProjectJour.leads.next_page_url })"
                    >
                        <span class="page-link">›</span>
                    </li>
                </ul>
            </nav>
        </div>

    </div>
</template>

<script>
export default {
  name: 'Paginate',
  props: ['projectid'],
  computed: {
    stateProjectJour () {
      return this.$store.getters.stateProjectJour
    },
    stateDataReady () {
      return this.$store.getters.stateDataReady
    },
    getLinks () {
      const links = this.stateProjectJour.leads.links.slice()
      links.pop()
      links.shift()
      return links
    }
  },
  methods: {
    getLeads (_projectId, _paginateNum, _paginatePath, _url) {
      if (!_url) return
      this.$store.dispatch('getLeads', { projectId: _projectId, paginateNum: _paginateNum, paginatePath: _paginatePath })
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
  }
}
</script>

<style scoped>
.journal__paginate__total {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
}
.journal__paginate__row {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}
.paginate__arrow {
    cursor: pointer;
}
.paginate__link {
    cursor: pointer;
}

@media screen and (max-width: 575px) {
    .journal__paginate__row {
        flex-direction: column;
    }
    .journal__paginate__total {
        position: static;
        transform: none;
    }
}

</style>
