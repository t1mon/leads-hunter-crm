<template>
    <div class="px-2 d-flex flex-column">
        <input
            v-model="name"
            placeholder="Введите имя"
            type="text"
            class="border border-danger rounded-2 bg-transparent text-secondary px-1 mb-2"
        >
        <button @click.prevent="setName()" class="btn btn-primary mb-0 py-1 px-3 w-100" >Отфильтровать</button>
    </div>
</template>

<script>
export default {
    name: "FilterName",
    watch: {
        stateParamsName(name) {
            if (!name) this.name = ''
        }
    },
    data() {
        return {
            name: ''
        }
    },
    computed: {
        stateParamsName() {
            return this.$store.getters['filterParams/stateParamsName']
        }
    },
    methods: {
        async setName() {
            this.$store.commit('filterParams/SET_NAME', this.name)
            await this.$store.dispatch('journalAll/getJournalAll')
        }
    },
    created() {
        const nameLS = localStorage.getItem('name')
        if (nameLS) {
            this.name = nameLS
            this.$store.commit('filterParams/SET_NAME', this.name)
        }
    },
    mounted() {
        $('#filterName').on('hidden.bs.dropdown', () => {
            this.name = this.stateParamsName
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
