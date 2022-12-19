<template>
    <!-- Modal -->
    <div class="modal fade" id="journalComments" tabindex="-1" role="dialog" aria-labelledby="journalCommentsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal" id="journalCommentsLabel">Комментарий:</h5>
                    <button ref="closeComments" type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div v-if="stateLoader" class="spinner-border text-primary d-block m-auto" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div v-if="!stateLoader" class="input-group input-group-dynamic">
                        <textarea
                            v-model="comment"
                            class="form-control" rows="5" placeholder="Введите комментарий" spellcheck="false"
                        ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        @click="addComment"
                        :disabled="stateLoader"
                        type="button"
                        class="btn bg-gradient-primary m-0">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "JournalComments",
    data() {
      return {
          comment: ''
      }
    },
    watch: {
        stateComment(comment) {
            this.comment = comment
        }
    },
    computed: {
        stateLoader() {
            return this.$store.getters['journalComments/stateLoader']
        },
        stateComment() {
            return this.$store.getters['journalComments/stateComment']
        },
        stateLeadId() {
            return this.$store.getters['journalComments/stateLeadId']
        }
    },
    methods: {
        async addComment() {
            this.$store.commit('loader/LOADER_TRUE')
            await axios.post('/api/v2/comment/add', {
                lead_id: this.stateLeadId,
                comment_body: this.comment
            }).then(response => {
                this.$refs.closeComments.click()
                this.$store.commit('loader/LOADER_FALSE')
                this.$store.dispatch('getToast', {
                    msg: 'Кмментарий добавлен!',
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
            await this.$store.dispatch('journalAll/getJournalAll')
        }
    }
}
</script>

<style scoped>

</style>
