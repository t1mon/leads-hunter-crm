<template>
    <div v-if="stateProjectJour" class="journal__paginate__row">

        <h6 class="journal__paginate__total m-0">Всего записей: {{ stateProjectJour.leads.meta.total }}</h6>

        <div class="journal__paginate__box">
            <nav>
                <ul class="pagination m-0 flex-wrap">
                    <li
                        v-for="link in stateProjectJour.leads.meta.links"
                        class="page-item paginate__link"
                        :class="{ 'active' : link.active }"
                        @click="getLeads(link.url)"
                    >
                        <span class="page-link">{{ link.label.replace(/[a-zA-Z]/g, '') }}</span>
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
    }
  },
  methods: {
    async getLeads (url) {
        if (!url) return
        const page = url.split('page=')[1].match(/\d+/)[0]
        await this.$store.dispatch('journalAll/getJournalAll', { id: this.projectid, page: page })
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
    margin-top: 5px;
}
.paginate__link {
    cursor: pointer;
}
@media screen and (max-width: 991px) {
    .journal__paginate__row {
        flex-direction: column;
    }
    .journal__paginate__total {
        position: static;
        transform: none;
    }
}

</style>
