<nav id="sidebar">
    <div class="custom-menu">
        <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="fa fa-bars"></i>
            <span class="sr-only">Toggle Menu</span>
        </button>
    </div>
    <h1><a href="{{ route('admin.index') }}" class="logo">{{ env('APP_NAME') }}</a></h1>
    <ul class="list-unstyled components mb-5">
        <li class="{{ request()->segment(2) == '' ? 'active' : '' }}">
            <a href="{{ route('admin.index') }}"><span class="fa fa-home mr-3"></span> Общее</a>
        </li>
        <li class="{{ request()->segment(2) == 'commands' ? 'active' : '' }}">
            <a href="{{ route('admin.commands.index') }}"><span class="fa fa-user mr-3"></span> Команды</a>
        </li>
        <li class="{{ request()->segment(2) == 'stocks' ? 'active' : '' }}">
            <a href="{{ route('admin.stocks.index') }}"><span class="fa fa-sticky-note mr-3"></span> Акции</a>
        </li>
        <li class="{{ request()->segment(2) == 'settings' ? 'active' : '' }}">
            <a href="{{ route('admin.settings.index') }}"><span class="fa fa-cog mr-3"></span> Настройки</a>
        </li>
        <li class="{{ request()->segment(2) == 'live' ? 'active' : '' }}">
            <a href="{{ route('admin.live') }}"><span class="fa fa-list mr-3"></span> Live</a>
        </li>
        <li>
            <a href="{{ route('logout') }}">
                <span class="fa fa-sign-out mr-3"></span>
                Выход
            </a>
        </li>
    </ul>
</nav>
