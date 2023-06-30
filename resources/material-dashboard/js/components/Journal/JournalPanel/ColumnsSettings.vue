<template>
    <div class="dropdown cursor-pointer">
        <button class="dropdown-toggle btn btn-info mb-0 py-1 px-3" id="columnSettings" data-bs-toggle="dropdown" aria-expanded="false">Настроить столбцы</button>
        <div class="dropdown-menu" aria-labelledby="columnSettings">
            <ul>
                <li v-for="(elem, elemIndex) in elems" :key="elemIndex">{{elem}}</li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: "ColumnsSettings",
    data() {
        return {
            elems: [
                'text1',
                'text2',
                'text3',
            ]
        }
    }
}
</script>

<style scoped>

</style>
