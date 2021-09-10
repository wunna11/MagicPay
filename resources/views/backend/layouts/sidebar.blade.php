<div class="scrollbar-sidebar">
    <div class="app-sidebar__inner">
        <ul class="vertical-nav-menu">
            <li class="app-sidebar__heading">Dashboards</li>
            <li>
                <a href="{{ route('admin.home') }}" class="{{ request()->path() === "admin" ? 'mm-active' : '' }}">
                    <i class="metismenu-icon pe-7s-home"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.admin-user.index') }}" class="{{ request()->path() === "admin/admin-user" ? 'mm-active' : '' }}">
                    <i class="metismenu-icon pe-7s-users"></i>
                    Admin User
                </a>
            </li>
        </ul>
    </div>
</div>