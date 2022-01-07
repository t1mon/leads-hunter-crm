<template>
    <div v-if="!checkCards" class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Projects table
                        </h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                        <table class="table align-items-center justify-content-center mb-0">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Project</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Budget</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Completion</th>
                                <th></th>
                            </tr>

                            <div v-if="stateIsLoading" class="spinner-grow" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>

                            </thead>
                            <tbody>
                            <tr v-for="project in filteredProject">
                                <td>
                                    <div class="d-flex px-2">
                                        <div>
                                            <img src="/assets/img/small-logos/logo-asana.svg" class="avatar avatar-sm rounded-circle me-2" alt="spotify">
                                        </div>
                                        <div class="my-auto">
                                            <a :href=" project.link "><h6 class="mb-0 text-sm"  >{{ project.name }}</h6> </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">$2,500</p>
                                </td>
                                <td>
                                    <span class="text-xs font-weight-bold">working</span>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="me-2 text-xs font-weight-bold">60%</span>
                                        <div>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="dropdown">
                                        <button data-bs-toggle="dropdown" class="btn btn-link text-secondary mb-0">
                                            <i class="fa fa-ellipsis-v text-xs" aria-hidden="true"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end me-sm-n4 me-n3" aria-labelledby="navbarDropdownMenuLink1" style="">
                                            <a class="dropdown-item" :href="project.link">Журнал</a>
                                            <a class="dropdown-item" href="javascript:;">Another action</a>
                                            <a class="dropdown-item" href="javascript:;">Something else here</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: 'Index',
    data: () => ({
    }),
    computed: {
        checkCards () {
            return this.$store.getters.stateCards
        },
        stateIsLoading () {
            return this.$store.getters.stateIsLoading
        },
        filteredProject () {
            return this.$store.getters.stateFilteredProjects
        },
        getProjects () {
            return this.$store.dispatch('getProjects')
        }
    },
    async created () {
        await this.getProjects
    }
}
</script>

<style scoped>
.dropdown-menu::before {
    display: none;
}
.dropdown {
    max-width: 80px;
}
</style>
