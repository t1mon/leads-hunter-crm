<template>
<div class="container bg-white py-4 mt-4 rounded-3">
    <div class="d-flex">
        <div class="w-50">
            <h3 class="fs-5 mb-4">Добавить нового пользователя</h3>

            <p class="mb-1 text-sm fw-bolder">Введите e-mail пользователя</p>
            <div class="input-group mb-3">
                <input type="text" class="form-control border border-1 px-2" placeholder="E-mail">
            </div>

            <p class="mb-1 text-sm fw-bolder">Назначте роль пользователя</p>
            <div class="input-group mb-3">
                <input disabled type="text" class="form-control border border-1 px-2 bg-white">
                <button class="btn btn-success m-0 px-3 text-xxs" type="button">Выбрать роль</button>
            </div>

            <p class="mb-1 text-sm fw-bolder">Настройте доступ к полям журнала</p>
            <div class="border border-1 rounded-2 p-2">
                <div class="d-flex flex-wrap mb-2">
                    <div
                        v-for="(field, index) in fields"
                        class="text-xxl p-1 px-3 bg-info text-white rounded-pill d-flex align-items-center m-1">
                        <span class="me-1">{{ field }}</span>
                        <i class="material-icons-round text-sm cursor-pointer"><span class="material-symbols-outlined">cancel</span></i>
                    </div>
                </div>
                <hr>
                <button class="btn btn-success m-0 px-3 text-xxs" type="button">Настроить</button>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "SettingsBasicPermissions",
    props: ['projectid'],
    data() {
      return {
          fields: null,
          assignedUsers: null
      }
    },
    computed: {
        stateProjectId() {
            return this.$store.getters['journalAll/stateProjectId']
        }
    },
    async created() {
        await axios.get(`/api/v2/project/permissions/index`, {
            params: {
                project_id: this.projectid
            }
        })
            .then(response => {
                this.assignedUsers = response.data
                console.log(response)
            })
            .catch(error => {
                console.log(error)
            })

        await axios.get('/api/v2/lead/get_fields', {
            params: {
                project_id: this.projectid
            }
        })
            .then(response => {
                this.fields = response.data
                console.log(response)
            })
            .catch(error => {
                console.log(error)
            })
    }
}
</script>

<style scoped>
</style>
