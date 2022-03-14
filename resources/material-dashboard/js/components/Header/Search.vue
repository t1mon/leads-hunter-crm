<template>
    <form :class="{ 'search--active' : focus || stateHeaderSearchPopup }" @submit.prevent="headerSearch(value)" action="#" class="search ms-md-auto pe-md-3 d-flex align-items-center">
        <div class="input-group input-group-outline">
            <header-search-popup v-if="stateHeaderSearchPopup"></header-search-popup>
            <button class="search__button">
                <span class="material-icons">search</span>
            </button>
            <label class="form-label">Search here</label>
            <input
                @focus="focus = true"
                @blur="focus = false"
                :style="stateHeaderSearchPopup ? 'border-bottom-left-radius: 0 !important; border-bottom-right-radius: 0 !important;' : '' " v-model="value" type="text" class="form-control">
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
            value: '',
            focus: false
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
.search {
    transition: 0.5s;
}
.search--active {
    width: 85%;
}
.is-focused .headerSearchPopup {
    border-color: #e91e63;
}

.search__button {
    background-color: transparent;
    border: none;
    padding: 0;
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    right: 5px;
    transform: translateY(-50%);
    z-index: 5;
}
.dark-version .search__button {
    color: rgba(255, 255, 255, 0.8);
}
.input-group.input-group-outline .form-control {
    padding-right: 25px !important;
}
@media screen and (max-width: 767px) {
    .search-active {
        width: 170%;
    }
}
</style>
