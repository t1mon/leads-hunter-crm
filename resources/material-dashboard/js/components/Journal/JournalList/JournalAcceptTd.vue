<template>
    <td>
        <p class="m-0 mb-1 text-xxs font-weight-normal text-center">
            <span v-if="accepted" class="text-success">{{ accepted }}</span>
            <span v-else class="text-danger text-uppercase">Лид свободен</span>
        </p>
        <div v-if="accepted" class="dropdown d-flex justify-content-center">
            <i :id="'deleteAcceptedUserFromLead' + id" class="material-icons-round text-danger cursor-pointer dropdown-toggle" data-bs-toggle="dropdown">
                <span class="material-symbols-outlined">cancel</span>
            </i>
            <div :aria-labelledby="'deleteAcceptedUserFromLead' + id" class="dropdown-menu text-center p-2 border border-1 rounded-2 border-primary">
                <span class="mb-2 d-block lh-sm fw-bolder">Снять пользователя <br> с лида?</span>
                <hr class="my-2">
                <div class="d-flex justify-content-between px-3">
                    <button
                        @click="dismissUser"
                        class="btn m-0 btn-success py-1 px-2 rounded-2 text-xxs">Да</button>
                    <button class="btn m-0 btn-danger py-1 px-2 rounded-2 text-xxs">Нет</button>
                </div>
            </div>
        </div>
        <hr class="my-2">
        <button
            @click="openModal"
            class="btn btn-info m-0 py-1 px-2 text-xxs rounded-2 d-block mx-auto" data-bs-toggle="modal" data-bs-target="#journalAcceptModal">
            Назначить
        </button>
    </td>
</template>

<script>
export default {
    name: "JournalAcceptTd",
    props: {
        accepted: {
            required: true
        },
        id: {
            required: true
        }
    },
    methods: {
        async dismissUser() {
            await axios.delete('/api/v2/lead/accept/dismiss', {
              data: {
                  lead_id: this.id
              }
            }).then(response => {
              if (response.status === 204) {
                  this.$store.dispatch('getToast', {
                      msg: 'Пользователь снят',
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
        },
        openModal() {
            this.$emit('openModal')
        }
    }
}
</script>

<style scoped>
.dropdown-menu::before {
    color: #e91e63;
}
.dropdown-toggle::after {
    display: none;
}
</style>
