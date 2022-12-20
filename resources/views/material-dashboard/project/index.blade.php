@extends('material-dashboard.layouts.app')

@section('content')

<div class="row">

        <div class="row mb-4 mb-md-0">
            <div class="col-md-8 me-auto my-auto text-left">
                <h5>@lang('projects.project')</h5>
            </div>
            <div class="col-md-12 my-auto text-end">
                <a href="{{ route('project.create') }}" class="btn bg-gradient-primary mb-0 mt-0 mt-lg-0">
                    <i class="material-icons text-white position-relative text-md pe-2">add</i> @lang('forms.actions.add')
                </a>
            </div>
        </div>
</div>

<projects></projects>

@endsection

