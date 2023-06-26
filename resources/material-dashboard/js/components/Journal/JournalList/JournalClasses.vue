<template>
    <td v-if="lead" class="text-white text-center">
        <div v-select class="select">
            <span
                :style="`background: #${findClass(lead.class_id).color}; color: ${findClass(lead.class_id) ? '#ffffff' : ''}`"
                class="select__title">{{ findClass(lead.class_id) ? findClass(lead.class_id).name : 'Не задан' }}</span>
            <span class="material-icons select__arrow">expand_more</span>
            <div class="select__content">
                <div
                    @click="getLeadClass(stateProjectJour.id, lead.id, 0)"
                    class="select__option"
                >Не задан</div>
                <div v-for="projectClass in stateProjectJour.classes" @click="getLeadClass(stateProjectJour.id, lead.id, projectClass.id)" class="select__option">
                    <div class="journal__row">
                        <span class="journal__class-name">{{ projectClass.name }}</span>
                        <span :style="'background:' + ' ' + '#' + projectClass.color" class="journal__class-color"></span>
                    </div>
                </div>
            </div>
        </div>
    </td>
</template>

<script>
export default {
    name: "JournalClasses",
    props: {
        lead: {
            required: true,
            type: Object
        }
    },
    computed: {
        stateProjectJour () {
            return this.$store.getters.stateProjectJour
        }
    },
    methods: {
        findClass(id) {
            if(!id) return false
            const _class = this.stateProjectJour.classes.find(el => {
                return el.id === id
            })
            return _class
        },
        async getLeadClass (projectId, leadId, classId) {
            const store = this.$store
            store.commit('loader/LOADER_TRUE')
            await axios.post(`/api/v1/project/${projectId}/journal/${leadId}/class/assign`, {
                class_id: classId
            })
                .then( (response) => {
                    store.commit('loader/LOADER_FALSE')
                    this.lead.class_id = classId
                })
                .catch(function (error) {
                    store.commit('loader/LOADER_FALSE')
                    console.log(error)
                })
        }
    }
}
</script>

<style scoped>
.journal__class-name {
    width: calc(100% - 20px);
    white-space: normal;
}
.journal__class-color {
    width: 15px;
    height: 15px;
    border-radius: 2px;
}
.journal__row {
    display: flex;
    justify-content: space-between;
}
</style>
