<template>
    <div class="modal fade" id="journalAcceptModal" tabindex="-1" role="dialog" aria-labelledby="journalAcceptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form @submit.prevent="accept" action="#" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal" id="journalAcceptModalLabel">Пользователи:</h5>
                    <button ref="closeJournalAcceptModal" type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div v-for="(user, index) in assignedUsers" class="form-check mb-1">
                        <input
                            v-model="assignedUser"
                            :value="user.name"
                            :id="'assignedUser' + index"
                            class="form-check-input" type="radio" name="assignedUser">
                        <label
                            :for="'assignedUser' + index"
                            class="custom-control-label">{{ user.name }}</label>
                    </div>
                    <div class="invalid-feedback" v-if="v$.assignedUser.required.$invalid && v$.$dirty">Выберите пользователя.</div>
                </div>
                <div class="modal-footer">
                    <button
                        type="submit"
                        class="btn bg-gradient-info m-0">Назначить</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { useVuelidate } from '@vuelidate/core'
import { required} from '@vuelidate/validators'
export default {
    name: "JournalAcceptModal",
    setup () {
        return { v$: useVuelidate() }
    },
    props: {
        assignedUsers: {
            required: true
        },
        lead: {}
    },
    data() {
        return {
            assignedUser: ''
        }
    },
    watch: {
        lead(lead) {
            this.assignedUser = lead.accepted_by
            this.v$.$reset()
        }
    },
    methods: {
        async accept() {

            const result = await this.v$.$validate()
            if (!result) {
                return
            }

            this.$store.commit('loader/LOADER_TRUE')
            const user = this.assignedUsers.find(el => {
                return el.name === this.assignedUser
            })
            await axios.post('/api/v2/lead/accept/assign', {
                lead_id: this.lead.id,
                acceptor_id: user.id
            }).then(response => {
                this.v$.$reset()
                this.assignedUser = ''
                this.$refs.closeJournalAcceptModal.click()
                if (response.status === 201) {
                    this.$store.dispatch('getToast', {
                        msg: 'Пользователь назначен',
                        settingsObj: {
                            type: 'success',
                            position: 'bottom-right',
                            timeout: 2000,
                            showIcon: true
                        }
                    })
                } else {
                    this.$store.dispatch('getToast', {
                        msg: 'Что-то пошло не так!',
                        settingsObj: {
                            type: 'warning',
                            position: 'bottom-right',
                            timeout: 2000,
                            showIcon: true
                        }
                    })
                }
                this.$store.commit('loader/LOADER_FALSE')
            }).catch(error => {
                console.log(error)
                this.$store.commit('loader/LOADER_FALSE')
                this.$store.dispatch('getToast', {
                    msg: 'Что-то пошло не так',
                    settingsObj: {
                        type: 'warning',
                        position: 'bottom-right',
                        timeout: 2000,
                        showIcon: true
                    }
                })
            })

            await this.$store.dispatch('journalAll/getJournalAll')
        }
    },
    validations () {
        return {
            assignedUser: { required }
        }
    },
}
</script>

<style scoped>
.is-invalid {
    outline: 1px solid tomato;
    border-radius: 4px;
}
.invalid-feedback {
    text-align: center;
    display: block;
    position:absolute;
    bottom: 2px;
    left: 0;
    width: 100%;
    color: #dc3545;
    font-size: 10px;
    margin: 0 !important;
}
</style>
