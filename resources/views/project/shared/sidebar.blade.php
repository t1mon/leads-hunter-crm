<ul class="navbar-nav navbar-sidenav">


    <li class="nav-item" role="presentation" data-toggle="tooltip" data-placement="right">
        <a class="nav-link {{ request()->route()->named('project.journal') ? 'active' : '' }}" href="{{ route('project.journal', $project) }}">
            <i class="fa fa-file-text" aria-hidden="true"></i>&nbsp;
            <span class="nav-link-text">@lang('projects.sidebar.journal')</span>
        </a>
    </li>

    <li class="nav-item" role="presentation" data-toggle="tooltip" data-placement="right">
        <a class="nav-link {{ request()->route()->named('project.hosts') ? 'active' : '' }}" href="{{ route('project.hosts', $project) }}">
            <i class="fa fa-globe" aria-hidden="true"></i>&nbsp;
            <span class="nav-link-text">@lang('projects.sidebar.hosts')</span>
        </a>
    </li>

    <li class="nav-item" role="presentation" data-toggle="tooltip" data-placement="right">
        <a class="nav-link {{ request()->route()->named('project.notification') ? 'active' : '' }}" href="{{ route('project.notification', $project) }}">
            <i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;
            <span class="nav-link-text">@lang('projects.sidebar.notification')</span>
        </a>
    </li>

    <li class="nav-item" role="presentation" data-toggle="tooltip" data-placement="right">
        <a class="nav-link {{ request()->route()->named('project.token') ? 'active' : '' }}" href="{{ route('project.token', $project) }}">
            <i class="fa fa-key" aria-hidden="true"></i>&nbsp;
            <span class="nav-link-text">@lang('projects.sidebar.integrations')</span>
        </a>
    </li>


</ul>
<ul class="navbar-nav sidenav-toggler">
    <li class="nav-item">
        <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
        </a>
    </li>
</ul>
