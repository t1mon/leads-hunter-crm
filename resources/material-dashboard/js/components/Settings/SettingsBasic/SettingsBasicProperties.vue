<template>
    <div class="row">
<!--            Кнопка включения-выключения-->
        <div class="card my-3">
            <div class="card-body">
                <div class="row  row-cols-2">
                    <div>
                        <h5 class="card-title text-center">Информация о проекте</h5>
                            <form>
                                <div class="input-group input-group-outline my-3">
                                    <label for="projectName" class="form-label">Название проекта</label>
                                    <input id="projectName" type="text" class="form-control">
                                </div>
                                <div class="input-group input-group-static mb-3">
                                    <label for="projectDesc" class="">Описание проекта</label>
                                    <textarea id="projectDesc" class="form-control" rows="5" placeholder="Введите описание" spellcheck="false"></textarea>
                                </div>
                                <button type="button" class="btn btn-primary">Изменить</button>
                            </form>
                    </div>

                    <!--                        часовой пояс-->
                    <div>
                        <h5 class="card-title">Часовой пояс</h5>
                        <div class="col-sm-4 col-6">
                            <label class="form-label ms-0">I'm</label>
                            <div class="choices" data-type="select-one" tabindex="0" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false">
                                <div class="choices__inner">
                                    <select class="form-control choices__input" name="choices-gender" id="choices-gender" hidden="" tabindex="-1" data-choice="active">
                                        <option value="Male">Male</option>
                                    </select>
                                    <div class="choices__list choices__list--single">
                                        <div class="choices__item choices__item--selectable" data-item="" data-id="1" data-value="Male" data-custom-properties="null" aria-selected="true">Male</div>
                                    </div>
                                </div>
                                <div class="choices__list choices__list--dropdown" aria-expanded="false">
                                    <input type="text" class="choices__input choices__input--cloned" autocomplete="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" aria-label="false" placeholder="" aria-activedescendant="choices--choices-gender-item-choice-1">
                                    <div class="choices__list" role="listbox">
                                        <div id="choices--choices-gender-item-choice-1" class="choices__item choices__item--choice choices__item--selectable is-highlighted" role="option" data-choice="" data-id="1" data-value="Female" data-select-text="Press to select" data-choice-selectable="" aria-selected="true">Female</div>
                                        <div id="choices--choices-gender-item-choice-2" class="choices__item choices__item--choice choices__item--selectable is-highlighted" role="option" data-choice="" data-id="1" data-value="Female" data-select-text="Press to select" data-choice-selectable="" aria-selected="true">male</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--                        {!! Form::model($project, ['method' => 'PUT', 'route' => ['project.update', $project] ]) !!}-->
                <!--                        <div class="mb-3">-->
                <!--                            {!! Form::label('project-name', trans('projects.properties.name'), ['class' => 'form-label']) !!}-->
                <!--                            {!! Form::text(-->
                <!--                            'properties[name]',-->
                <!--                            $project->name,-->
                <!--                            [-->
                <!--                            'id' => 'project-name',-->
                <!--                            'placeholder' => trans('projects.properties.name'),-->
                <!--                            'class' => 'form-control border p-2'-->
                <!--                            ]-->
                <!--                            ) !!}-->
                <!--                        </div>-->
                <!--                        <div class="mb-3">-->
                <!--                            {!! Form::label('project-description', trans('projects.properties.description'), ['class' => 'form-label']) !!}-->
                <!--                            {!! Form::textarea(-->
                <!--                            'settings[description]',-->
                <!--                            $project->settings['description'],-->
                <!--                            [-->
                <!--                            'id' => 'project-description',-->
                <!--                            'placeholder' => trans('projects.properties.description'),-->
                <!--                            'class' => 'form-control border p-2'-->
                <!--                            ]-->
                <!--                            ) !!}-->
                <!--                        </div>-->
                <!--                        <div class="mb-3 text-center">-->
                <!--                            {!! Form::submit(trans('projects.button-change'), ['class' => 'btn btn-primary'] ) !!}-->
                <!--                        </div>-->
                <!--                        {!! Form::close() !!}-->


                <!--                        {!! Form::model($project, ['method' => 'PUT', 'route' => ['project.update', $project] ]) !!}-->
                <!--                        @php-->
                <!--                        $timezones_raw = timezone_identifiers_list();-->
                <!--                        $timezones = [];-->
                <!--                        foreach($timezones_raw as $item)-->
                <!--                        $timezones[$item] = $item;-->
                <!--                        @endphp-->

                <!--                        <p class="card-text">-->
                <!--                            {!! Form::select('settings[timezone]', $timezones, $project->timezone) !!}-->
                <!--                        </p>-->

                <!--                        <p class="card-text">-->
                <!--                            {!! Form::submit(trans('projects.button-change'), ['class' => 'btn btn-primary'] ) !!}-->
                <!--                        </p>-->
                <!--                        {!! Form::close() !!}-->

<!--                        кнопка выключения-->
                <!--                        {!! Form::model($project, ['method' => 'PUT', 'route' => ['project.update', $project] ]) !!}-->
                <!--                        {!! Form::hidden('settings[enabled]', $project->settings['enabled'] ? 0 : 1) !!}-->
                <!--                        <div class="text-center">-->
                <!--                            <button class="btn btn-{{$project->settings['enabled'] ? 'danger' : 'secondary'}} btn-lg">-->
                <!--                                <i class="fa fa-power-off" aria-hidden="true"></i>-->
                <!--                            </button>-->
                <!--                        </div>-->
                <!--                        {!! Form::close() !!}-->

            </div>
        </div>

<!--            Срок годности лида-->
        <div class="col">
            <div class="card my-3">
                <div class="card-body">
                    <h5 class="card-title">Срок годности лида</h5>
<!--                        {!! Form::model($project, ['method' => 'PUT', 'route' => ['project.update', $project] ]) !!}-->
<!--                        <p class="card-text">-->
<!--                            {!! Form::label('leadValidDays', 'Кол-во дней', []) !!}-->
<!--                            {!! Form::number('settings[leadValidDays]', $project->settings['leadValidDays'], ['id' => 'leadValidDays', 'class' => 'form-control']) !!}-->
<!--                        </p>-->

<!--                        <p class="card-text">-->
<!--                            {!! Form::submit(trans('projects.button-change'), ['class' => 'btn btn-primary'] ) !!}-->
<!--                        </p>-->
<!--                        {!! Form::close() !!}-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "SettingsBasicProperties"
}
</script>

<style scoped>

</style>
