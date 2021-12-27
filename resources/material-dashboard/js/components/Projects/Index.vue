<template>
    <div>This is PROJECTS</div>
</template>

<script>
import axios from 'axios'

export default {
    name: 'Index',
    data: () => ({
        endpoint: `/api/v1/project`,
        isLoading: false
    }),
    created () {

        axios
            .get(this.endpoint)
            .then(response => {
                this.isLoading = false
                this.isLiked = true
                this.count++
            })
            .catch(() => {
                this.isLoading = false
            })

    }
}
</script>

<style scoped>

</style>
