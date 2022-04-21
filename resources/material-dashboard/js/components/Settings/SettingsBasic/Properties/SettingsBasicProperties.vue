<template>
    <div class="card">
        <div class="card-body p-3 p-md-4">
            <div class="row justify-content-between flex-column-reverse flex-lg-row">
                <div class="row col-lg-10 col-12">
                    <settings-basic-info></settings-basic-info>

                    <div class="col-12 col-md-6 col-xxl d-flex flex-column justify-content-between mt-3 mt-md-0">
                        <settings-basic-timezone></settings-basic-timezone>
                        <settings-basic-term></settings-basic-term>
                    </div>

                    <settings-basic-hosts></settings-basic-hosts>
                </div>

                <!--                        кнопка выключения-->
                <div class="d-flex col justify-content-center justify-content-sm-end mb-2 mb-md-0">
                    <div class="p-0 col-auto">
                        <h6 class="lh-1 text-center">Отключить проект</h6>
                        <button class="btn btn-danger m-auto d-block btn-sm"><i class="fa fa-power-off"></i></button>
                    </div>
                </div>
            </div>
            <settings-basic-hosts-list></settings-basic-hosts-list>
        </div>
    </div>
</template>

<script>
import SettingsBasicInfo from './SettingsBasicInfo'
import SettingsBasicTimezone from './SettingsBasicTimezone'
import SettingsBasicTerm from './SettingsBasicTerm'
import SettingsBasicHosts from './SettingsBasicHosts'
import SettingsBasicHostsList from './SettingsBasicHostsList'

export default {
    name: 'SettingsBasicProperties',
    components: {
        SettingsBasicInfo,
        SettingsBasicTimezone,
        SettingsBasicTerm,
        SettingsBasicHosts,
        SettingsBasicHostsList
    },
    data () {
        return {

        }
    },
    methods: {
    }
}
</script>

<style scoped>

</style>
