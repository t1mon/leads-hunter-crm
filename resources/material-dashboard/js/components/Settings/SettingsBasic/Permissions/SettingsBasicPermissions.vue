<template>
<h1>Hello</h1>
</template>

<script>
export default {
    name: "SettingsBasicPermissions",
    props: ['projectid'],
    computed: {
        stateProjectId() {
            return this.$store.getters['journalAll/stateProjectId']
        }
    },
    created() {
        axios.get(`/api/v2/project/permissions/index`, {
            params: {
                project_id: this.projectid
            }
        })
            .then(response => {
                console.log(response)
            })
            .catch(error => {
                console.log(error)
            })
    }
}
</script>

<style scoped>

</style>
