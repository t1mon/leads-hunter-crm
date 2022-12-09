{{--{{--}}
{{--{{    dd(\Illuminate\Support\Facades\Route::current()->project) }}--}}
{{--    dd(request()->route()->named('project.*'))--}}
{{--}}--}}
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 rounded-0 border-radius-xl fixed-start bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header h-auto">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 p-3" href="{{ route('home') }}">
            <img src="{{ asset('media/img/logo/logo.svg') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold text-white">PRO</span>
        </a>
    </div>
    <hr class="horizontal light m-0">
    <div class="collapse navbar-collapse  w-auto h-auto max-height-vh-100 h-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @auth()
            <li class="nav-item mt-0">
                <a data-bs-toggle="collapse" href="#ProfileNav" class="nav-link text-white m-0 rounded-0 px-2" aria-controls="ProfileNav" role="button" aria-expanded="false">
                    <img src="{{ asset('media/img/avatar.jpg') }}" class="avatar">
                    <span class="nav-link-text ps-1"> {{ Auth::user()->name }} </span>
                </a>
                <div class="collapse" id="ProfileNav" style="">
                    <ul class="nav ">
                        <li class="nav-item">
                            <a class="nav-link text-white m-0 rounded-0" href="{{ route('users.show', Auth::user()) }}">
                                <span class="sidenav-mini-icon font-weight-bolder"> MP </span>
                                <span class="sidenav-normal ps-1"> {{ __('users.public_profile') }} </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white m-0 rounded-0" href=" {{ route('users.edit') }} ">
                                <i class="material-icons-round opacity-10">manage_accounts</i>
                                <span class="sidenav-normal ps-1">{{ __('users.settings') }} </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/logout') }}"
                               class="nav-link text-white m-0 rounded-0"
                               onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                <i class="material-icons-round opacity-10">logout</i>
                                <span class="sidenav-normal ps-1"> @lang('auth.logout') </span>
                            </a>

                            <form id="logout-form" class="d-none" action="{{ url('/logout') }}" method="POST">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </div>
            </li>
            <hr class="horizontal light m-0">
                <li class="nav-item">
                    <a class="nav-link m-0 rounded-0 px-2" href="{{ route('home') }}">
                        <i class="material-icons-round opacity-10">dashboard</i>
                        <span class="nav-link-text ms-2 ps-1">Панель управления</span>
                    </a>
                </li>
                @if(request()->route()->named('project.*') && !request()->route()->named('project.index'))
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#dashboardsExamples" class="nav-link text-white active m-0 px-2 rounded-0 collapsed" aria-controls="dashboardsExamples" role="button" aria-expanded="false">
                            <i class="material-icons-round opacity-10">list</i>
                            <span class="nav-link-text ms-2 ps-1">Проекты</span>
                        </a>
                        <div class="collapse pt-2" id="dashboardsExamples">
                            <ul class="nav ">
                                @foreach(auth()->user()->getAllprojects() as $_project)
                                    <li class="nav-item {{ $project->id === $_project->id ? 'active' : '' }}">
                                        <a style="white-space: normal" class="align-items-start nav-link text-white m-0 rounded-0 p-2 mw-100 {{ $project->id === $_project->id ? 'active' : '' }}" href="{{ route('project.journal', $_project) }}">
                                            <span class="sidenav-mini-icon font-weight-bolder me-1">{{ $_project->id }})</span>
                                            <span class="">{{ $_project->name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @endif
            <hr class="horizontal light m-0">
            @if(request()->route()->named('project.*') && !request()->route()->named('project.index'))

                <li class="nav-item {{ request()->route()->named('project.journal') ? 'active' : '' }}">
                        <a class="nav-link m-0 rounded-0 {{ request()->route()->named('project.journal') ? 'active' : '' }}" href="{{ route('project.journal', $project ) }}">
                            <i class="material-icons-round opacity-10">text_snippet</i>
                            <span class="nav-link-text ms-2 ps-1">ЕЖЛ</span>
                        </a>
                </li>

                @if($project->isOwner() or Auth::user()->isManagerFor($project))
                    <li class="nav-item {{ request()->route()->named('project.journal') ? 'active' : '' }}">
                        <a class="nav-link m-0 rounded-0 {{ request()->route()->named('project.log') ? 'active' : '' }}" href="{{ route('project.log', $project ) }}">
                            <i class="material-icons-round opacity-10">notes</i>
                            <span class="nav-link-text ms-2 ps-1">Лог</span>
                        </a>
                    </li>

                    <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#pagesExamples" class="nav-link text-white m-0 rounded-0" aria-controls="pagesExamples" role="button" aria-expanded="false">
                                <i class="material-icons-round">settings</i>
                                <span class="nav-link-text ms-2 ps-1">Настройки</span>
                            </a>
                            <div class="collapse " id="pagesExamples">
                                <ul class="nav ">
                                    <li class="nav-item ">
                                        <a class="nav-link text-white m-0 rounded-0" href={{route('project.settings-basic', $project)}}>
                                            <span class="sidenav-normal  ms-2  ps-1"> Основное </span>
                                        </a>
                                    </li>
                                    <li class="nav-item ">
                                        <a class="nav-link text-white m-0 rounded-0" href={{route('project.settings-sync', $project)}}>
                                            <span class="sidenav-normal  ms-2  ps-1"> Синхронизации </span>
                                        </a>
                                    </li>
                                    <li class="nav-item ">
                                        <a class="nav-link text-white m-0 rounded-0" href={{route('project.token', $project->id)}}>
                                            <span class="sidenav-normal  ms-2  ps-1"> @lang('projects.sidebar.integrations') </span>
                                        </a>
                                    </li>

                                    <li class="nav-item ">
                                        <a class="nav-link text-white " href={{route('project.integrations', $project->id)}}>
                                            <span class="sidenav-normal  ms-2  ps-1"> (+) Интеграции </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                    </li>
                @endif

                    {{-- <hr class="horizontal light">
                    <h6 class="ps-4  ms-2 text-uppercase text-xs font-weight-bolder text-white">Вспомогательное меню</h6>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('project.hosts', $project) }}">
                            <i class="material-icons-round">receipt_long</i>
                            <span class="nav-link-text ms-2 ps-1">@lang('projects.sidebar.hosts')</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('project.notification', $project) }}">
                            <i class="material-icons-round">receipt_long</i>
                            <span class="nav-link-text ms-2 ps-1">@lang('projects.sidebar.forwarding')</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('project.token', $project) }}">
                            <i class="material-icons-round">receipt_long</i>
                            <span class="nav-link-text ms-2 ps-1">@lang('projects.sidebar.integrations')</span>
                        </a>
                    </li> --}}
            @endif
            @endauth

        </ul>
    </div>
</aside>
