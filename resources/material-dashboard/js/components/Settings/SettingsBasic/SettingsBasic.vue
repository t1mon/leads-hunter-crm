<template>
    <div>
<!--        Навигационное меню-->
        <div>
            <ul class="nav nav-tabs">
<!--                Настройки хостов-->
                <li class="nav-item">
                    <a  class="nav-link active" data-bs-toggle="tab" href="#hosts">Свойства проекта</a>
                </li>

<!--                Настройки разрешений пользователей-->
                <li class="nav-item">
                    <a  class="nav-link" data-bs-toggle="tab" href="#users">Настройки доступа</a>
                </li>

<!--                Классы проекта-->
                <li class="nav-item">
                    <a  class="nav-link" data-bs-toggle="tab" href="#classes">Классы</a>
                </li>

            </ul>
        </div>

        <div class="tab-content">
<!--            Настройки хостов-->
            <div class="tab-pane fade show active" id="hosts" role="tabpanel">
                <settings-basic-properties></settings-basic-properties>
                <settings-basic-hosts></settings-basic-hosts>
            </div>

<!--            Настройки разрешений пользователей-->
            <div class="tab-pane fade" id="users" role="tabpanel">
                <settings-basic-users></settings-basic-users>
            </div>

<!--            Классы проекта-->
            <div class="tab-pane fade" id="classes" role="tabpanel">
                <settings-basic-classes></settings-basic-classes>
            </div>

        </div>
    </div>
</template>

<script>

import SettingsBasicProperties from './SettingsBasicProperties'
import SettingsBasicHosts from './SettingsBasicHosts'
import SettingsBasicUsers from './SettingsBasicUsers'
import SettingsBasicClasses from './SettingsBasicClasses'

export default {
    name: 'SettingsBasic',
    components: {
        SettingsBasicProperties,
        SettingsBasicHosts,
        SettingsBasicUsers,
        SettingsBasicClasses
    }
}
</script>

<style scoped>

</style>
