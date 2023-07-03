<template>
    <div class="dropdown cursor-pointer">
        <button class="dropdown-toggle btn btn-info mb-0 py-1 px-3" id="columnSettings" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">Настроить столбцы</button>
        <div class="dropdown-menu px-2" aria-labelledby="columnSettings">
            <div
                v-for="(column, columnIndex) in columns"
                class="form-check m-0 p-0 d-flex align-items-center mb-2">
                <input
                    @change="changeColumnsSettings(columnIndex)"
                    v-model="columnsSettings"
                    :value="columnIndex"
                    :id="columnIndex"
                    class="form-check-input m-0 me-1"
                    type="checkbox"
                >
                <label class="form-check-label m-0 text-xxs lh-sm d-flex align-items-center" :for="columnIndex">
                    {{ columnIndex }}
                </label>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ColumnsSettings",
    props: {
        columns: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            columnsSettings: []
        }
    },
    methods: {
        changeColumnsSettings(column) {
            this.$emit('changeColumnsSettings', column)
        }
    },
    created() {
        for (const key in this.columns) {
            if(this.columns[key]) {
                this.columnsSettings.push(key)
            }
        }
    }
}
</script>

<style scoped>
.dropdown-menu::before {
    color: #e91e63;
}
</style>
