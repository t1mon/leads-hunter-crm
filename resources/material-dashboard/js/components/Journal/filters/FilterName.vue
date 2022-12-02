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
    data() {
        return {
            name: ''
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
