<template>
    <!--                        часовой пояс-->
    <form action="#">
        <h5 class="card-title card-title--timezone">Часовой пояс</h5>
        <div class="mb-3">
            <div
                :class="{'is-open is-focused' : dropdown}"
                class="choices" data-type="select-one">
                <div
                    @click.prevent="openDropdown()"
                    class="p-0 choices__inner">
                    <select class="form-control choices__input" name="choices-gender" id="choices-gender" hidden="">
                        <option value="Male">Male</option>
                    </select>
                    <div class="choices__list choices__list--single pb-2">
                        <div class="mb-0 choices__item choices__item--selectable">{{ timezone }}</div>
                    </div>
                </div>
                <div
                    :class="{'is-active is-focused' : dropdown}"
                    class="choices__list choices__list--dropdown">
                    <input ref="dropdownSearch" type="text" class="choices__input choices__input--cloned">
                    <div class="choices__list">
                        <div
                            v-for="item in data"
                            @click="selectTimezone($event)"
                            :class="{ 'choices__item--active' : timezone === item }"
                            class="choices__item choices__item--dropdown"
                        >{{ item }}</div>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary">Изменить</button>
    </form>
</template>

<script>
export default {
    name: 'SettingsBasicTimezone',
    data () {
        return {
            dropdown: false,
            timezone: 'test1',
            data: [
                'test1',
                'test2',
                'test3',
                'test4'
            ]
        }
    },
    updated () {
        if (this.dropdown) {
            window.addEventListener('click', this.closeDropdown)
        } else {
            window.removeEventListener('click', this.closeDropdown)
        }
    },
    methods: {
        openDropdown () {
            this.dropdown = !this.dropdown
            if (this.dropdown) {
                setTimeout(() => {
                    this.$refs.dropdownSearch.focus()
                }, 200)
            } else {
                this.$refs.dropdownSearch.blur()
            }
        },
        closeDropdown (e) {
            if (!e.target.closest('.is-open')) {
                this.dropdown = false
            }
        },
        selectTimezone (e) {
            this.timezone = e.target.textContent
            this.openDropdown(event)
        }
    }
}
</script>

<style scoped>
.card-title--timezone {
    margin-bottom: 13px !important;
}

.choices__item--dropdown {
    cursor: pointer;
}
.choices__item--active {
    background-color: #f0f2f5;
    color: #344767;
}
.choices__item--dropdown:hover {
    background-color: #f0f2f5;
    color: #344767;
}
</style>
