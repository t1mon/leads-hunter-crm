<template>
    <div>
        <div class="border border-1 rounded-2 mb-1">
            <input
                v-model="date"
                :class="{ 'text-danger' : date }"
                class="form-control p-0"
                type="datetime-local"
            >
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <form @submit.prevent="setDate" action="#" class="lh-1">
                <button
                    :disabled="!date || date === serverDate"
                    :class="{ 'opacity-4' : !date || date === serverDate}"
                    class="btn m-0 btn-success py-0 px-1 rounded-2 text-xxs"
                    type="submit"
                >ok</button>
            </form>

            <button @click="resetDate" class="btn m-0 btn-warning py-0 px-2 rounded-2 text-xxs">Сброс</button>

            <div v-if="serverDate" class="dropdown lh-1">
                <button class="btn m-0 btn-danger py-0 px-2 rounded-2 text-xxs" id="deleteCallBackDate" data-bs-toggle="dropdown">&times;</button>
                <div class="dropdown-menu text-center p-2 border border-1 rounded-2 border-primary" aria-labelledby="deleteCallBackDate">
                    <span class="mb-2 d-block">Удалить дату?</span>
                    <div class="d-flex justify-content-between px-3">
                        <button class="btn m-0 btn-success py-1 px-2 rounded-2 text-xxs">Нет</button>
                        <button @click="deleteDate" class="btn m-0 btn-danger py-1 px-2 rounded-2 text-xxs">Да</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: "CallBackDate",
    props: {
        callBack: {
            type: String
        },
        leadId: {
            required: true
        }
    },
    data() {
        return {
            date: '',
            serverDate: ''
        }
    },
    methods: {
        addZero(num) {
            const _num = num < 10 ? `0${num}` : num
            return _num
        },
        async deleteDate() {
            this.$store.commit('loader/LOADER_TRUE')
            await axios.delete('/api/v2/lead/nextcall/clear', {
                data: {
                    lead_id: this.leadId
                }
            }).then(response => {
                this.$store.commit('loader/LOADER_FALSE')
                this.$store.dispatch('getToast', {
                    msg: 'Дата удалена!',
                    settingsObj: {
                        type: 'success',
                        position: 'bottom-right',
                        timeout: 2000,
                        showIcon: true
                    }
                })
                this.serverDate = ''
                this.date = ''
            }).catch(error => {
                this.$store.commit('loader/LOADER_FALSE')
                console.log(error)
            })
        },
        async setDate() {
            this.$store.commit('loader/LOADER_TRUE')
            await axios.post('/api/v2/lead/nextcall/add', {
                lead_id: this.leadId,
                datetime: this.date.replace(/T/, ' ')
            }).then(response => {
                this.$store.commit('loader/LOADER_FALSE')
                this.$store.dispatch('getToast', {
                    msg: 'Дата установлена!',
                    settingsObj: {
                        type: 'success',
                        position: 'bottom-right',
                        timeout: 2000,
                        showIcon: true
                    }
                })
                this.serverDate = this.date
            }).catch(error => {
                this.$store.commit('loader/LOADER_FALSE')
                console.log(error)
            })
        },
        resetDate() {
            this.date = this.serverDate
        }
    },
    created() {
        if(this.callBack) {
            const date = new Date(this.callBack)
            this.serverDate = `${date.getFullYear()}-${this.addZero(date.getMonth() + 1)}-${this.addZero(date.getDate())}T${this.addZero(date.getUTCHours())}:${this.addZero(date.getUTCMinutes())}`
        }
        this.date = this.serverDate
    }
}
</script>

<style scoped>
.dropdown-menu::before {
    color: #e91e63;
}
</style>
