<template>
    <div class="px-2 d-flex flex-column">
        <input
            v-model="phone"
            @input="phoneNum"
            placeholder="Введите телефон"
            type="text"
            class="border border-danger rounded-2 bg-transparent text-secondary px-1 mb-2"
        >
        <button @click.prevent="setPhone()" class="btn btn-primary mb-0 py-1 px-3 w-100">Отфильтровать</button>
    </div>
</template>

<script>

export default {
    name: "FilterPhone",
    watch: {
        stateParamsPhone(phone) {
            if (!phone) this.phone = ''
        }
    },
    data() {
        return {
            phone: ''
        }
    },
    computed: {
        stateParamsPhone() {
            return this.$store.getters['filterParams/stateParamsPhone']
        }
    },
    methods: {
        phoneNum() {
          this.phone = this.phone.replace(/\D/g, '')
        },
        async setPhone() {
            const phone = this.phone.replace(/\D/g, '')
            this.$store.commit('filterParams/SET_PHONE', phone)
            await this.$store.dispatch('journalAll/getJournalAll')
        }
    },
    created() {
        const phoneLS = localStorage.getItem('phone')
        if (phoneLS) {
            this.phone = phoneLS
            this.$store.commit('filterParams/SET_PHONE', this.phone)
        }
    },
    mounted() {
        $('#filterPhone').on('hidden.bs.dropdown', () => {
            this.phone = this.stateParamsPhone
        })
    }
}
</script>

<style scoped>
input::placeholder {
    color: #7B809A;
}
input:focus {
    border-color: #e91e63;
}
</style>
