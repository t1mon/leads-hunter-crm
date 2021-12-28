<template>
    <div class="row mt-lg-4 mt-2">
        <div v-for="project in projects" class="col-lg-4 col-md-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex mt-n2">
                        <div class="avatar avatar-xl bg-gradient-dark border-radius-xl p-2 mt-n4">
                            <img src="/assets/img/small-logos/logo-asana.svg" alt="slack_logo">
                        </div>
                        <div class="ms-3 my-auto">
                            <a :href="project.link"><h6 class="mb-0">{{ project.name }}</h6></a>
                        </div>
                        <div class="ms-auto">
                            <div class="dropdown">
                                <button class="btn btn-link text-secondary ps-0 pe-2" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v text-lg" aria-hidden="true"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end me-sm-n4 me-n3" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" :href="project.link">Журнал</a>
                                    <a class="dropdown-item" href="javascript:;">Another action</a>
                                    <a class="dropdown-item" href="javascript:;">Something else here</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm mt-3"> If everything I did failed - which it doesn't, I think that it actually succeeds. </p>
                    <hr class="horizontal dark">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="text-sm mb-0">5</h6>
                            <p class="text-secondary text-sm font-weight-normal mb-0">Participants</p>
                        </div>
                        <div class="col-6 text-end">
                            <h6 class="text-sm mb-0">02.03.22</h6>
                            <p class="text-secondary text-sm font-weight-normal mb-0">Due date</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    name: 'Index',
    data: () => ({
        endpoint: `/api/v1/project`,
        isLoading: false,
        projects: null
    }),
    methods: {
        getProjects(){
            this.isLoading = true
            axios
                .get(this.endpoint)
                .then(({ data }) => {
                    this.isLoading = false
                    this.projects = data.data
                })
                .catch(() => {
                    this.isLoading = false
                })
        },
        test() {
            this.$store.commit('increment')
            console.log(this.$store.state.count)
        }
    },

    async  created () {
        await this.getProjects()
    }
}
</script>

<style scoped>
.dropdown-menu::before {
    display: none;
}
</style>
