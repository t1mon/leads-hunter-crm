<template>
    <div>
<!--        Форма добавления нового хоста-->
        <div class="card mt-5">
            <div class="card-body">
<!--                <h5 class="card-title">@lang('projects.hosts.add-host')</h5>-->
<!--                {!! Form::open(['route' => ['host.store',$project], 'class' => 'd-flex']) !!}-->
<!--                {!! Form::hidden('project_id', $project->id) !!}-->
<!--                <p class="card-text">-->
<!--                    {!! Form::text('host', null, ['class' => 'form-control', 'placeholder' => trans('projects.hosts.add-host-placeholder')]) !!}-->
<!--                    {!! Form::submit(trans('projects.button-add'), ['class' => 'btn btn-primary']) !!}-->
<!--                </p>-->

<!--                {!! Form::close() !!}-->
            </div>
        </div>

<!--        Таблица с хостами-->
        <div class="card my-3">
            <div class="card-body">
<!--                <h5 class="card-title">@lang('projects.hosts.list')</h5>-->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
<!--                            <th>@lang('projects.hosts.host')</th>-->
<!--                            <th>@lang('projects.hosts.action')</th>-->
                        </tr>
                        </thead>
                        <tbody>
<!--                        @foreach($hosts as $host)-->
<!--                        <tr>-->
<!--                            <td class="text-nowrap">{{$host->host}}</td>-->
<!--                            <td class="text-nowrap">-->
<!--                                {!! Form::model($host, ['method' => 'DELETE', 'route' => ['host.destroy', [$project,$host]]]) !!}-->
<!--                                {!! Form::button('<i class="fa fa-trash" aria-hidden="true"></i>', ['class' => 'btn btn-danger btn-sm', 'type' => 'submit']) !!}-->
<!--                                {!! Form::close() !!}-->
<!--                            </td>-->
<!--                        </tr>-->
<!--                        @endforeach-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "SettingsBasicHosts"
}
</script>

<style scoped>

</style>
