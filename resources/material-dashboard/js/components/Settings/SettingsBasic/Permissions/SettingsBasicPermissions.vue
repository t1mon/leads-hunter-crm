<template>
<div class="container bg-white py-4 mt-4 rounded-3">
    <div class="d-flex">
        <div class="w-50">
            <h3 class="fs-5 mb-4">Добавить нового пользователя</h3>

            <p class="mb-1 text-sm fw-bolder">Введите e-mail пользователя</p>
            <div class="input-group mb-3">
                <input type="text" class="form-control border border-1 px-2" placeholder="E-mail">
                <button class="permissions__btn--add btn btn-success m-0 px-0" type="button" id="button-addon2">Добавить</button>
            </div>

            <p class="mb-1 text-sm fw-bolder">Назначте роль пользователя</p>
            <div class="input-group mb-3">
                <input disabled type="text" class="form-control border border-1 px-2 bg-white">
                <button class="permissions__btn--add btn btn-success m-0 px-0 text-xxs" type="button" id="button-addon2">Выбрать роль</button>
            </div>

            <p class="mb-1 text-sm fw-bolder">Настройте доступ к полям журнала</p>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "SettingsBasicPermissions",
    props: ['projectid'],
    computed: {
        stateProjectId() {
            return this.$store.getters['journalAll/stateProjectId']
        }
    },
    created() {
        axios.get(`/api/v2/project/permissions/index`, {
            params: {
                project_id: this.projectid
            }
        })
            .then(response => {
                console.log(response)
            })
            .catch(error => {
                console.log(error)
            })
    }
}
</script>

<style scoped>
.permissions__btn--add {
    width: 120px;
}
</style>
