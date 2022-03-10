<template>
    <form @submit.prevent="headerSearch(value)" action="#" class="ms-md-auto pe-md-3 d-flex align-items-center">
        <div class="input-group input-group-outline">
            <button class="search__button">
                <span class="material-icons">search</span>
            </button>
            <label class="form-label">Search here</label>
            <input v-model="value" type="text" class="form-control">
            <header-search-popup v-if="stateHeaderSearchPopup"></header-search-popup>
        </div>
    </form>
</template>

<script>
import HeaderSearchPopup from '../Others/HeaderSearchPopup'

export default {
    name: "HeaderSearch",
    components: {
        HeaderSearchPopup
    },
    data () {
        return {
            value: ''
        }
    },
    methods: {
        headerSearch (value) {
            this.$store.dispatch('headerSearch', value)
        }
    },
    computed: {
        stateHeaderSearchPopup () {
            return this.$store.getters.stateHeaderSearchPopup
        }
    }
}
</script>

<style scoped>
.search__button {
    background-color: transparent;
    border: none;
    padding: 0;
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    right: 0;
    transform: translateY(-50%);
    z-index: 5;
}
.dark-version .search__button {
    color: rgba(255, 255, 255, 0.8);
}
</style>
