<template>
    <div>
        <div class="border border-1 rounded-2 mb-1">
            <input v-model="date" class="form-control p-0 text-danger" type="date">
        </div>
        <div class="d-flex justify-content-between">
<!--            <button class="btn m-0 btn-success py-0 px-1 rounded-2 text-xxs">✅</button>-->
<!--            <button class="btn m-0 btn-danger py-0 px-2 rounded-2 text-xxs">&times;</button>-->
            <button class="btn m-0 btn-success py-0 px-1 rounded-2 text-xxs">ok</button>
            <button class="btn m-0 btn-danger py-0 px-2 rounded-2 text-xxs">Сброс</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "CallBackDate",
    data() {
        return {
            date: ''
        }
    }
}
</script>

<style scoped>

</style>
