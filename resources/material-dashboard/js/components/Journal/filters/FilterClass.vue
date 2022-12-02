<template>
    <div class="px-2">
        <div class="form-check m-0 p-0 d-flex align-items-center mb-2">
            <input
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
            data-bs-toggle="dropdown"
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
        allClasses(val) {
            if (val) {
                this.classes = this.stateProjectJour.classes.map(projectClass => {
                    return projectClass.id
                })
            } else {
                this.classes = []
            }
        }
    },
    computed: {
        stateProjectJour () {
            return this.$store.getters.stateProjectJour
        }
    },
    methods: {
        setClasses() {
            var dropdown = new bootstrap.Dropdown('#filterClass', 'hide')
        }
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
