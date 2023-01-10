<template>
    <div class="modal fade" id="journalCompany" tabindex="-1" role="dialog" aria-labelledby="journalCompanyLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal" id="journalCompanyLabel">Компания:</h5>
                    <button ref="closeCompany" type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group input-group-outline my-3">
                        <input
                            v-model="company"
                            placeholder="Введите компанию"
                            type="text"
                            class="form-control"
                        >
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        @click="addDeleteCompany"
                        type="button"
                        class="btn bg-gradient-primary m-0">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "JournalCompanyModal",
    data() {
        return {
            company: ''
        }
    },
    computed: {
        stateLeadId() {
            return this.$store.getters['journalCompany/stateLeadId']
        }
    },
    methods: {
        async addDeleteCompany() {
            this.$store.commit('loader/LOADER_TRUE')
            if (!this.company) {
                await axios.delete('/api/v2/lead/company/clear', {
                    data: {
                        lead_id: this.stateLeadId
                    }
                }).then(response => {
                    this.$store.commit('journalCompany/SET_COMPANY', '')
                    this.$refs.closeCompany.click()
                    this.$store.commit('loader/LOADER_FALSE')
                    this.$store.dispatch('getToast', {
                        msg: 'Компания удалёна!',
                        settingsObj: {
                            type: 'success',
                            position: 'bottom-right',
                            timeout: 2000,
                            showIcon: true
                        }
                    })
                    console.log(response)
                }).catch(error => {
                    this.$store.commit('loader/LOADER_FALSE')
                    console.log(error)
                })
            } else {
                await axios.post('/api/v2/lead/company/add', {
                    lead_id: this.stateLeadId,
                    company: this.company
                }).then(response => {
                    this.$store.commit('journalCompany/SET_COMPANY', this.company)
                    this.$refs.closeCompany.click()
                    this.$store.commit('loader/LOADER_FALSE')
                    this.$store.dispatch('getToast', {
                        msg: 'Компания обновлёна!',
                        settingsObj: {
                            type: 'success',
                            position: 'bottom-right',
                            timeout: 2000,
                            showIcon: true
                        }
                    })
                    console.log(response)
                }).catch(error => {
                    this.$store.commit('loader/LOADER_FALSE')
                    console.log(error)
                })
            }
        }
    }
}
</script>

<style scoped>

</style>
