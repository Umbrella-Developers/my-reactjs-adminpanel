
<div id="ajax-loader" style="display:none;">
  <div class="d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="spinner-border" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
</div>
<div class="mt-4">
    <div class="row">
        <div class="col">
            <h5 class="fw-bold">Welcome Back,</h5>
            <h4 class="fw-bold text-secondary">{{ ucfirst(Auth::user()->first_name)}} {{ ucfirst(Auth::user()->last_name)}}</h4>
        </div>
        <div class="col">
            <nav
                class="layout-navbar navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                id="layout-navbar"
            >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>
                

                <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                <ul class="navbar-nav flex-row align-items-center ms-auto">
                    
                    <!-- User -->
                    <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                        <img src="{{getAvatar(Auth::user())}}" alt class="w-px-40 h-auto rounded-circle" />
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                        <a class="dropdown-item" href="{{ route('users.editProfile') }}">
                            <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">
                                <img src="{{getAvatar()}}" alt class="w-px-40 h-auto rounded-circle" />
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block">{{ ucfirst(Auth::user()->first_name) }} {{ ucfirst(Auth::user()->last_name)}}</span>
                                <small class="text-muted">{{Auth::user()->roles()->first()->name}}</small>
                            </div>
                            </div>
                        </a>
                        </li>
                        <li>
                        <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('users.editProfile') }}">
                                <i class="bx bx-user me-2"></i>
                                <span class="align-middle">My Profile</span>
                            </a>
                        </li>
                        @if(!Auth::user()->hasRole('Donor'))
                            <li>
                                <a class="dropdown-item" href="{{ route('configurations.index') }}">
                                    <i class="bx bx-cog me-2"></i>
                                    <span class="align-middle">Settings</span>
                                </a>
                            </li>
                        @endif
                        <li>
                        <div class="dropdown-divider"></div>
                        </li>
                        <li>
                        <a class="dropdown-item" href="/logout">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                        </li>
                    </ul>
                    </li>
                    <!--/ User -->
                </ul>
                </div>
            </nav>
        </div>
    </div>
    <!-- Header -->
    <hr />
    <!-- / Header -->
</div>
<div class="container-xxl mt-4">
    <div class="row">
        <div class="col alert-wrapper">
        @if ($errors->any() && !session('update'))
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        </div>
    </div>
</div>