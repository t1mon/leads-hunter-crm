<template>
    <div class="px-2">
        <div class="form-check m-0 p-0 d-flex align-items-center mb-2">
            <input
                @change="setAllClasses($event)"
                v-model="allClasses"
                id="allClasses"
                class="form-check-input m-0 me-1"
                type="checkbox"
            >
            <label class="form-check-label m-0 text-xxs lh-sm d-flex align-items-center font-weight-bolder" for="allClasses">
                Выбрать все
            </label>
        </div>
        <div
            v-for="(projectClass, projectClassIndex) in stateProjectJour.classes"
            class="form-check m-0 p-0 d-flex align-items-center mb-2">
            <input
                v-model="classes"
                :value="projectClass.id"
                :id="'projectClass' + projectClassIndex"
                class="form-check-input m-0 me-1"
                type="checkbox"
            >
            <label class="form-check-label m-0 text-xxs lh-sm d-flex align-items-center" :for="'projectClass' + projectClassIndex">
                <span
                    v-if="projectClass.color"
                    :style="'background: #' + projectClass.color"
                    class="color rounded-2 me-1"
                ></span>
                {{ projectClass.name }}
            </label>
        </div>
        <hr class="my-1">
        <button
            @click.prevent="setClasses()"
            class="btn btn-primary mb-0 py-1 px-3 w-100"
        >Отфильтровать</button>
    </div>
</template>

<script>

export default {
    name: "FilterClass",
    data() {
      return {
          allClasses: false,
          classes: []
      }
    },
    watch: {
        classes(arr) {
            if (arr.length === 0) this.allClasses = false
            if (arr.length === this.stateProjectJour.classes.length) {
                this.allClasses = true
            } else {
                this.allClasses = false
            }
        },
        stateParamsClasses(arr) {
            if (arr.length === 0) this.classes = []
        }
    },
    computed: {
        stateProjectJour () {
            return this.$store.getters.stateProjectJour
        },
        stateParamsClasses() {
            return this.$store.getters['filterParams/stateParamsClasses']
        }
    },
    methods: {
        setAllClasses() {
            if (this.allClasses) {
                this.classes = this.stateProjectJour.classes.map(projectClass => {
                    return projectClass.id
                })
            } else {
                this.classes = []
            }
        },
        async setClasses() {
            console.log(this.classes)
            this.$store.commit('filterParams/SET_CLASSES', this.classes)
            await this.$store.dispatch('journalAll/getJournalAll')
            $('#filterClass').dropdown('hide')
        }
    },
    mounted() {
        const classesLS = localStorage.getItem('classes')
        if (classesLS) {
            this.classes = JSON.parse(classesLS)
            if (this.classes.length === this.stateProjectJour.classes.length) {
                this.allClasses = true
            } else {
                this.allClasses = false
            }
            this.$store.commit('filterParams/SET_CLASSES', this.classes)
        }

        $('#filterClass').on('hidden.bs.dropdown', () => {
            this.classes = this.stateParamsClasses.map(projectClass => {
                return projectClass
            })
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
