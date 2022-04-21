<template>
    <div class="col-12 col-md-6 col-xxl">
        <h5 class="card-title">Информация о проекте</h5>
        <form action="#">
            <div class="input-group input-group-dynamic mb-4">
                <label for="projectName" class="form-label">Название проекта</label>
                <input id="projectName" type="text" class="form-control">
            </div>
            <div class="input-group input-group-static mb-3">
                <label for="projectDesc" class="">Описание проекта</label>
                <textarea id="projectDesc" class="form-control" rows="5" placeholder="Введите описание"></textarea>
            </div>
            <button type="button" class="btn btn-primary">Изменить</button>
        </form>
    </div>
</template>

<script>
export default {
    name: "SettingsBasicInfo"
}
</script>

<style scoped>

</style>
