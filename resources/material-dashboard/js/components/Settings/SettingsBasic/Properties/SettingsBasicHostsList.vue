<template>
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
</template>

<script>
export default {
    name: "SettingsBasicHostsList"
}
</script>

<style scoped>

</style>
