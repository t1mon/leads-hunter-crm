<template>
<div class="container p-2 p-sm-4 mt-4 rounded-3">
    <p class="fw-bold fs-4 mb-2 text-uppercase text-center text-danger">Владелец проекта: {{ owner.name }}</p>
    <div class="d-flex flex-column-reverse flex-lg-row">
        <div class="w-100 w-lg-50 p-2">
            <h3 class="fs-5 mb-4">Назначенные пользователи:</h3>

            <div v-if="assignedUsersLoad" class="spinner-border text-default m-auto" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p
                v-if="!assignedUsersLoad && (!assignedUsers || assignedUsers.length === 0)"
               class="mb-1 text-sm fw-bolder"
            >Нет назначенных пользователей.</p>
            <div
                v-if="!assignedUsersLoad && assignedUsers && assignedUsers.length > 0"
            >
                <form
                    v-for="(user, userIndex) in assignedUsers"
                    @submit.prevent="changeRole(userIndex)"
                    :key="userIndex"
                    action="#"
                    class="border border-1 border-success rounded-2 p-2 mb-3">

                    <div class="d-flex justify-content-between">
                        <p class="m-0 text-md text-dark fw-bolder">{{ user.name }}</p>

                        <div class="dropdown">
                            <i :id="'deleteUserFromProject' + userIndex" class="material-icons-round text-danger cursor-pointer dropdown-toggle" data-bs-toggle="dropdown">
                                <span class="material-symbols-outlined">cancel</span>
                            </i>
                            <div :aria-labelledby="'deleteUserFromProject' + userIndex" class="dropdown-menu text-center p-2 border border-1 rounded-2 border-primary">
                                <span class="mb-2 d-block lh-sm fw-bolder">Снять пользователя с проекта?</span>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between px-3">
                                    <button
                                        @click="dismissUser(user.id)"
                                        class="btn m-0 btn-success py-1 px-2 rounded-2 text-xxs">Да</button>
                                    <button class="btn m-0 btn-danger py-1 px-2 rounded-2 text-xxs">Нет</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-2 text-info fw-bold">

                    <p class="mb-1 text-sm fw-bolder">E-mail пользователя</p>
                    <div
                        :class="{'is-invalid' : !assignedUsers[userIndex].email || !isEmail(userIndex) }"
                        class="input-group mb-3">
                        <input
                            @input="assignedUsers[userIndex].change = true"
                            v-model="assignedUsers[userIndex].email"
                            type="text" class="form-control border border-1 px-2" placeholder="E-mail">
                        <div class="invalid-feedback" v-if="!assignedUsers[userIndex].email">Обязательное поле.</div>
                        <div class="invalid-feedback" v-else-if="!isEmail(userIndex)">Неверный формат</div>
                    </div>

                    <p class="mb-1 text-sm fw-bolder">Роль пользователя</p>
                    <div
                        :class="{'is-invalid' : !assignedUsers[userIndex].role}"
                        class="input-group mb-3">
                        <select
                            @change="assignedUsers[userIndex].change = true"
                            v-model="assignedUsers[userIndex].role"
                            class="form-select border border-1 px-2">
                            <option value="manager">Менеджер</option>
                            <option value="junior_manager">Младший менеджер</option>
                            <option value="watcher">Наблюдатель</option>
                        </select>
                        <div class="invalid-feedback" v-if="!assignedUsers[userIndex].role">Обязательное поле.</div>
                    </div>

                    <p class="mb-1 text-sm fw-bolder">Доступ к полям журнала</p>
                    <div class="border border-1 rounded-2 p-2 mb-3">
                        <div v-if="fieldsLoad" class="spinner-border text-default m-auto" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div
                            v-else
                            class="d-flex flex-wrap mb-2">
                            <span v-if="assignedUsers[userIndex].view_fields.length === 0" class="text-sm text-warning">Здесь будут показаны выбранные поля журнала.</span>
                            <div
                                v-for="(field, fieldIndex) in assignedUsers[userIndex].view_fields"
                                :key="fieldIndex"
                                class="text-xxs p-1 px-2 bg-info text-white rounded-pill d-flex align-items-center m-1">
                                <span class="me-1">{{ field.front }}</span>
                                <i
                                    @click="deleteFieldUser(userIndex, fieldIndex)"
                                    class="material-icons-round text-sm cursor-pointer"><span class="material-symbols-outlined">cancel</span></i>
                            </div>
                        </div>
                        <hr>
                        <button class="btn btn-secondary m-0 px-3 text-xxl" type="button" data-bs-toggle="modal" :data-bs-target="'#permissionsFields' + userIndex">Добавить поля</button>
                    </div>

                    <button
                        v-if="assignedUsers[userIndex].change"
                        class="btn btn-success m-0 d-block ms-auto" type="submit">Сохранить</button>

<!--                    модалка каждого пользователя-->
                    <div class="modal fade py-2" :id="'permissionsFields' + userIndex" tabindex="-1" role="dialog" :aria-labelledby="'permissionsFieldsLabel' + userIndex" aria-hidden="true">
                        <div class="modal-dialog h-100 my-0" role="document">
                            <form action="#" class="modal-content mh-100">
                                <div class="modal-header">
                                    <h5 class="modal-title font-weight-normal" :id="'permissionsFieldsLabel' + userIndex">Отметьте поля</h5>
                                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div v-if="fieldsLoad" class="spinner-border text-default m-auto" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div v-else class="modal-body overflow-auto">
                                    <div
                                        v-for="(field, fieldIndex) in fields"
                                        :key="fieldIndex"
                                        class="form-check">
                                        <input
                                            @change="assignedUsers[userIndex].change = true"
                                            v-model="assignedUsers[userIndex].view_fields"
                                            :value="field"
                                            :id="field.back + 'User' + userIndex"
                                            class="form-check-input" type="checkbox">
                                        <label class="custom-control-label" :for="field.back + 'User' + userIndex">{{field.front}}</label>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button
                                        @click="selectAllFieldsUser(userIndex)"
                                        :disabled="fieldsLoad"
                                        type="submit"
                                        class="btn btn-info m-0">Выбрать все</button>
                                    <button
                                        @click="clearAllFieldsUser(userIndex)"
                                        :disabled="fieldsLoad"
                                        type="submit"
                                        class="btn btn-warning m-0">Очистить</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </form>

            </div>
        </div>

        <form @submit.prevent="assignUser" action="#" class="w-100 w-lg-50 p-2">
            <h3 class="fs-5 mb-4">Добавить нового пользователя:</h3>

            <p class="mb-1 text-sm fw-bolder">Введите e-mail пользователя</p>
            <div
                :class="{'is-invalid' : v$.email.$invalid && v$.$dirty}"
                class="input-group mb-3">
                <input
                    v-model="email"
                    type="text" class="form-control border border-1 px-2" placeholder="E-mail">
                <div class="invalid-feedback" v-if="v$.email.required.$invalid && v$.$dirty">Обязательное поле.</div>
                <div class="invalid-feedback" v-if="v$.email.email.$invalid && v$.$dirty">Неверный формат</div>
            </div>

            <p class="mb-1 text-sm fw-bolder">Назначте роль пользователя</p>
            <div
                :class="{'is-invalid' : v$.role.$invalid && v$.$dirty}"
                class="input-group mb-3">
                <select
                    v-model="role"
                    class="form-select border border-1 px-2">
                    <option value="" disabled>Роль не выбрана</option>
                    <option value="manager">Менеджер</option>
                    <option value="junior_manager">Младший менеджер</option>
                    <option value="watcher">Наблюдатель</option>
                </select>
                <div class="invalid-feedback" v-if="v$.role.required.$invalid && v$.$dirty">Обязательное поле.</div>
            </div>

            <p class="mb-1 text-sm fw-bolder">Настройте доступ к полям журнала</p>
            <div class="border border-1 rounded-2 p-2 mb-3">
                <div v-if="fieldsLoad" class="spinner-border text-default m-auto" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div
                    v-else
                    class="d-flex flex-wrap mb-2">
                    <span v-if="checkedFields.length === 0" class="text-sm text-warning">Здесь будут показаны выбранные поля журнала.</span>
                    <div
                        v-for="(field, index) in checkedFields"
                        :key="index"
                        class="text-xxs p-1 px-2 bg-info text-white rounded-pill d-flex align-items-center m-1">
                        <span class="me-1">{{ field.front }}</span>
                        <i
                            @click="deleteField(index)"
                            class="material-icons-round text-sm cursor-pointer"><span class="material-symbols-outlined">cancel</span></i>
                    </div>
                </div>
                <hr>
                <button class="btn btn-secondary m-0 px-3 text-xxl" type="button" data-bs-toggle="modal" data-bs-target="#permissionsFields">Добавить поля</button>
            </div>
            <button class="btn btn-success m-0 d-block ms-auto" type="submit">Добавить пользователя</button>
        </form>
    </div>
</div>

<div class="modal fade py-2" id="permissionsFields" tabindex="-1" role="dialog" aria-labelledby="permissionsFieldsLabel" aria-hidden="true">
        <div class="modal-dialog h-100 my-0" role="document">
            <form action="#" class="modal-content mh-100">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal" id="permissionsFieldsLabel">Отметьте поля</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div v-if="fieldsLoad" class="spinner-border text-default m-auto" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div v-else class="modal-body overflow-auto">
                    <div
                        v-for="(field, index) in fields"
                        :key="index"
                        class="form-check">
                        <input
                            v-model="checkedFields"
                            :value="field"
                            :id="field.back"
                            class="form-check-input" type="checkbox">
                        <label class="custom-control-label" :for="field.back">{{field.front}}</label>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button
                        @click="selectAllFields"
                        :disabled="fieldsLoad"
                        type="submit"
                        class="btn btn-info m-0">Выбрать все</button>
                    <button
                        @click="clearAllFields"
                        :disabled="fieldsLoad"
                        type="submit"
                        class="btn btn-warning m-0">Очистить</button>
                </div>
            </form>
        </div>
</div>
</template>

<script>
import { useVuelidate } from '@vuelidate/core'
import { required, email } from '@vuelidate/validators'

const isemail = require('isemail')

export default {
    name: "SettingsBasicPermissions",
    setup () {
        return { v$: useVuelidate() }
    },
    props: ['projectid'],
    data() {
      return {
          owner: '',
          email: '',
          role: '',
          fields: null,
          checkedFields: [],
          assignedUsers: null,
          fieldsLoad: false,
          assignedUsersLoad: false,
          checkedFieldsModal: [],
      }
    },
    methods: {
        isEmail(index) {
            return isemail.validate(this.assignedUsers[index].email)
        },
        async changeRole(index) {
            if( !this.assignedUsers[index].email || !this.isEmail(index) || !this.assignedUsers[index].role ) {
                return
            }

            this.$store.commit('loader/LOADER_TRUE')
            await axios.put(`/api/v2/project/permissions/change`, {
                permissions_id: this.assignedUsers[index].id,
                role: this.assignedUsers[index].role,
                fields: this.assignedUsers[index].view_fields.map( el => { return el.back })
            }).then(response => {
                console.log(response)
                this.$store.commit('loader/LOADER_FALSE')
            }).catch(error => {
                console.log(error)
                this.$store.commit('loader/LOADER_FALSE')
            })

            this.$store.commit('loader/LOADER_TRUE')
            await this.getUsers()
            this.$store.commit('loader/LOADER_FALSE')
        },
        async getUsers() {
            this.assignedUsersLoad = true
            await axios.get(`/api/v2/project/permissions/index`, {
                params: {
                    project_id: this.projectid
                }
            })
                .then(response => {
                    this.owner = response.data.data.find(el => {
                        return el.owner
                    })
                    this.assignedUsers = response.data.data.filter(el => {
                        return !el.owner
                    })

                    this.assignedUsers.forEach(el => {
                        const arr = []
                        for (const key in el.view_fields) {
                            const obj = {
                                front: el.view_fields[key],
                                back: key
                            }
                            arr.push(obj)
                        }
                        el.view_fields= arr
                    })

                    this.assignedUsersLoad = false
                    console.log(response)
                })
                .catch(error => {
                    this.assignedUsersLoad = false
                    console.log(error)
                })
        },
        clearData() {
            this.email = ''
            this.role = ''
            this.checkedFields = []
        },
        async dismissUser(id) {
            this.$store.commit('loader/LOADER_TRUE')
            await axios.delete('/api/v2/project/permissions/dismiss', {
                data: {
                    permissions_id: id
                }
            }).then(response => {
                this.$store.dispatch('getToast', {
                    msg: 'Пользователь снят с проекта!',
                    settingsObj: {
                        type: 'success',
                        position: 'bottom-right',
                        timeout: 2000,
                        showIcon: true
                    }
                })
                this.$store.commit('loader/LOADER_FALSE')
            }).catch(error => {
                this.$store.commit('loader/LOADER_FALSE')
                this.$store.dispatch('getToast', {
                    msg: 'Что-то пошло не так!',
                    settingsObj: {
                        type: 'danger',
                        position: 'bottom-right',
                        timeout: 2000,
                        showIcon: true
                    }
                })
                console.log(error)
            })

            this.$store.commit('loader/LOADER_TRUE')
            await this.getUsers()
            this.$store.commit('loader/LOADER_FALSE')
        },
        async assignUser() {
            const result = await this.v$.$validate()
            if (!result) {
                return
            }

            this.$store.commit('loader/LOADER_TRUE')

            const fields = this.checkedFields.map(el => {
                return el.back
            })
            await axios.post('/api/v2/project/permissions/assign', {
                project_id: this.projectid,
                users: [{
                    email: this.email,
                    role: this.role,
                    fields: fields
                }],
            }).then(response => {
                this.v$.$reset()
                this.clearData()
                this.$store.commit('loader/LOADER_FALSE')
                this.$store.dispatch('getToast', {
                    msg: 'Лид добавлен!',
                    settingsObj: {
                        type: 'success',
                        position: 'bottom-right',
                        timeout: 2000,
                        showIcon: true
                    }
                })
            }).catch(error => {
                this.$store.dispatch('getToast', {
                    msg: 'Что-то пошло не так!',
                    settingsObj: {
                        type: 'danger',
                        position: 'bottom-right',
                        timeout: 2000,
                        showIcon: true
                    }
                })
                this.$store.commit('loader/LOADER_FALSE')
                console.log(error)
            })

            this.$store.commit('loader/LOADER_TRUE')
            await this.getUsers()
            this.$store.commit('loader/LOADER_FALSE')

        },
        deleteFieldUser(userIndex, fieldIndex) {
            this.assignedUsers[userIndex].view_fields.splice(fieldIndex, 1)
            this.assignedUsers[userIndex].change = true
        },
        deleteField(index) {
            this.checkedFields.splice(index, 1)
        },
        clearAllFieldsUser(index) {
            this.assignedUsers[index].view_fields = []
            this.assignedUsers[index].change = true
        },
        selectAllFieldsUser(index) {
            this.assignedUsers[index].view_fields = this.fields.map(el => {
                return el
            })
            this.assignedUsers[index].change = true
        },
        selectAllFields() {
            this.checkedFields = this.fields.map(el => {
                return el
            })
        },
        clearAllFields() {
            this.checkedFields = []
        }
    },
    validations () {
        return {
            email: { required, email },
            role: { required }
        }
    },
    created() {
        this.fieldsLoad = true
        this.getUsers()

        axios.get('/api/v2/lead/get_fields', {
            params: {
                project_id: this.projectid
            }
        })
            .then(response => {
                const arr = []
                for (const key in response.data) {
                    const obj = {
                        front: response.data[key],
                        back: key
                    }
                    arr.push(obj)
                }
                this.fields = arr
                this.fieldsLoad = false
                console.log(response)
            })
            .catch(error => {
                this.fieldsLoad = true
                console.log(error)
            })
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
select {
    background-position: right 8px center;
}
.is-invalid {
    outline: 1px solid tomato;
    border-radius: 4px;
}
.invalid-feedback {
    display: block;
    position:absolute;
    top: -2px;
    left: 2px;
    width: 100%;
    color: #dc3545;
    font-size: 10px;
    margin: 0 !important;
}
</style>
