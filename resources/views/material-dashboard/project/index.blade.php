@extends('material-dashboard.layouts.app')

@section('content')
<div class="row">
    @if($projects->isNotEmpty())

        <div class="row mb-4 mb-md-0">
            <div class="col-md-8 me-auto my-auto text-left">
                <h5>@lang('projects.project')</h5>
                <p>This is the paragraph where you can write more details about your projects. Keep you user engaged by <br> providing meaningful information.</p>
            </div>
            <div class="col-lg-4 col-md-12 my-auto text-end">
                <a href="{{ route('project.create') }}" class="btn bg-gradient-primary mb-0 mt-0 mt-md-n9 mt-lg-0">
                    <i class="material-icons text-white position-relative text-md pe-2">add</i> @lang('forms.actions.add')
                </a>
            </div>
        </div>
        @include ('material-dashboard/project/_list')

    @else
        <a href="{{ route('project.create') }}" class="btn btn-primary btn-lg">Создай свой первый проект</a>
    @endif
</div>

<index></index>

@endsection

