<nav class="header-navbar navbar-expand-lg navbar navbar-fixed align-items-center navbar-shadow navbar-brand-center" data-nav="brand-center">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu ficon"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a></li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="">
                       <img src="{{ storage_asset('NewTheme/images/white_logo.png') }}" class="logo-big">
                    </a>
                </li>
            </ul>
            <p class="font-size-16 mb-0">
                @yield('breadcrumbTitle')
            </p>
        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">
            <li class="nav-item dropdown dropdown-user">
                <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="avatar">
                        <img class="round" src="{{ storage_asset('NewTheme/images/avatar7.png')}}" alt="avatar" height="40" width="40">
                        <span class="avatar-status-online"></span>
                    </span>
                    <div class="user-nav d-sm-flex d-none">
                        <span class="user-name fw-bolder">{{ ucwords(Auth::guard('agentUserWL')->user()->name) }}</span>
                        <span class="user-status">Available</span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user" style="width: 14rem;">
                    <a class="dropdown-item" href="{{ url('wl/rp/profile') }}">
                        Edit Personal Info
                    </a>
                    <a class="dropdown-item" href="{!! URL::route('wl/rp/logout') !!}">
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>