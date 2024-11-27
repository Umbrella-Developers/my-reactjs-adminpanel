<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo text-center">
        <a href="{{ url('/') }}" class="app-brand-link">
            <img src="{{ getLogo()}}" alt class="w-px-60 h-auto rounded-circle" />
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <ul class="menu-inner py-1 sidebar-navigation">
        <!-- Dashboard -->
        @if(!Auth::user()->hasRole('Backup Role Association'))
            <li class="menu-item {{ request()->routeIs('donations') ? 'active' : '' }}">
                <a href="{{ url('/donations') }}" class="menu-link donation-link active">
                    <div data-i18n="Analytics">Donations</div>
                </a>
            </li>
            @if(!Auth::user()->hasRole('Donor'))
                <li class="menu-item {{ request()->routeIs('donors.index') ? 'active' : '' }}">
                    <a href="{{ url('/donors') }}" class="menu-link donor-link">
                        <i class="menu-icon tf-icons bx bx-donate-heart w-px-24" ></i>
                        <div data-i18n="Analytics">Donors</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
                    <a href="{{ url('/users') }}" class="menu-link staff-link">
                        <i class="menu-icon tf-icons bx bx-group"></i>
                        <div data-i18n="Analytics">Staff</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('configurations.index') ? 'active' : '' }}">
                    <a href="{{ url('/configurations') }}" class="menu-link staff-link">
                        <i class="menu-icon tf-icons bx bx-cog"></i>
                        <div data-i18n="Analytics">Configurations</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                    <a href="{{ url('/roles') }}" class="menu-link staff-link">
                        <i class="menu-icon tf-icons bx bx-key"></i>
                        <div data-i18n="Analytics">Roles</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('logs.index') ? 'active' : '' }}">
                    <a href="{{ url('/logs') }}" class="menu-link staff-link ">
                        <i class="fas menu-icon  fa-history"></i>
                        <div data-i18n="Analytics">Logs</div>
                    </a>
                </li>
            @endif
        @endif
    </ul>
    <div class="profile-icon">
        
            <a class="nav-link dropdown-toggle hide-arrow " href="javascript:void(0);" data-bs-toggle="dropdown">
            <div class="">
                <img src="{{ getAvatar(Auth::user()) }}" alt class="w-px-50 h-auto rounded-circle" />
            </div>
            </a>
            
        </li>
        <!--/ User -->
        
    
    </div>
</aside>
