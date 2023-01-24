<template>
    <td>
        <p class="m-0 mb-1 text-xxs font-weight-normal text-center">
            <span v-if="accepted" class="text-success">{{ accepted }}</span>
            <span v-else class="text-danger text-uppercase">Лид свободен</span>
        </p>
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
        }
    },
    methods: {
        openModal() {
            this.$emit('openModal')
        }
    }
}
</script>

<style scoped>

</style>
