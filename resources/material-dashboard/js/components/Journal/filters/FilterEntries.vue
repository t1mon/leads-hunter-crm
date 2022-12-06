<template>
    <div class="px-2">
        <div class="form-check m-0 p-0 d-flex align-items-center mb-2">
            <input
                v-model="entries"
                value=""
                name="entries"
                class="form-check-input m-0 me-1"
                type="radio"
                id="entriesAll"
            >
            <label class="form-check-label m-0 text-xxs lh-sm d-flex align-items-center" for="entriesAll">
                ВСЕ
            </label>
        </div>
        <div class="form-check m-0 p-0 d-flex align-items-center mb-2">
            <input
                v-model="entries"
                value="1"
                name="entries"
                class="form-check-input m-0 me-1"
                type="radio"
                id="entries1"
            >
            <label class="form-check-label m-0 text-xxs lh-sm d-flex align-items-center" for="entries1">
                Первичное
            </label>
        </div>
        <div class="form-check m-0 p-0 d-flex align-items-center mb-2">
            <input
                v-model="entries"
                value="2"
                name="entries"
                class="form-check-input m-0 me-1"
                type="radio"
                id="entries2"
            >
            <label class="form-check-label m-0 text-xxs lh-sm d-flex align-items-center" for="entries2">
                Вторичное
            </label>
        </div>
        <hr class="my-1">
        <button
            @click.prevent="setEntries()"
            class="btn btn-primary mb-0 py-1 px-3 w-100"
        >Отфильтровать</button>
    </div>
</template>

<script>
export default {
    name: "FilterEntries",
    data() {
        return {
            entries: ''
        }
    },
    watch: {
        stateParamsEntries(entries) {
            if (!entries) this.entries = ''
        }
    },
    computed: {
        stateParamsEntries() {
            return this.$store.getters['filterParams/stateParamsEntries']
        }
    },
    methods: {
        async setEntries() {
            this.$store.commit('filterParams/SET_ENTRIES', this.entries)
            await this.$store.dispatch('journalAll/getJournalAll')
            $('#filterEntries').dropdown('hide')
        }
    },
    created() {
        const entriesLS = localStorage.getItem('entries')
        if (entriesLS) {
            this.entries = entriesLS
            this.$store.commit('filterParams/SET_ENTRIES', +this.entries)
        }
    },
    mounted() {
        $('#filterEntries').on('hidden.bs.dropdown', () => {
            this.entries = this.stateParamsEntries
        })
    }
}
</script>

<style scoped>
.form-check:not(.form-switch) .form-check-input {
    margin-left: 0 !important;
}
.color {
    display: inline-block;
    width: 15px;
    height: 15px;
}
</style>
