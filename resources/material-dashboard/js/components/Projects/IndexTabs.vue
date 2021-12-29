<template>
    <div class="col-lg-4 col-md-6 m-3">
        <div class="nav-wrapper position-relative end-0">
            <ul class="nav nav-pills nav-fill p-1" role="tablist">
                <li class="nav-item">
                    <a @click="checkList" class="nav-link mb-0 px-0 py-1 active " data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">
                        <i class="material-icons text-lg position-relative">home</i>
                        <span class="ms-1">List</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a @click="checkCards" class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                        <i class="material-icons text-lg position-relative">email</i>
                        <span class="ms-1">Cards</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: 'indexTabs',
    methods: {
        checkCards () {
            this.$store.commit('checkCards')
        },
        checkList () {
            this.$store.commit('checkList')
        }
    }
}
</script>

<style scoped>
.nav-wrapper {
    border-radius: 0.75rem;
}
.dark-version .nav-pills {
    background: #202940;
    color: #ffffff;
}
.dark-version .nav-link {
    color: #ffffff;
}
.dark-version .nav-item .active {
    color: #344667;
}
</style>
