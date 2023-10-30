<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-dark">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item">
                    <a class="nav-link menu-toggle" href="#">
                        <svg width="21" height="16" viewBox="0 0 21 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M0.0507812 0.821655H20.7266V3.72937H0.0507812V0.821655ZM0.0507812 6.79462H20.7266V9.70233H0.0507812V6.79462ZM20.7266 12.7676H0.0507812V15.6753H20.7266V12.7676Z"
                                fill="#3E5C76" />
                        </svg>
                    </a>
                </li>
            </ul>
            @yield('breadcrumbTitle')
        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">
            <li class="nav-item">
                <div style="width: 400px;">
                    <span>
                        <b>UTC Time &nbsp; : &nbsp; </b>
                        <span id="datetime" class="clock">Loading...</span>
                    </span>
                </div>
            </li>

            <!-- Notifications popup -->
            <li class="nav-item dropdown dropdown-notification">
                <a class="nav-link read-notification" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <svg width="23" height="23" viewBox="0 0 23 23" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.166 4.84354C6.85234 4.84354 4.16604 7.52983 4.16604 10.8435V14.4293L3.45894 15.1364C3.17294 15.4224 3.08738 15.8525 3.24217 16.2262C3.39695 16.5999 3.76158 16.8435 4.16604 16.8435H16.166C16.5705 16.8435 16.9351 16.5999 17.0899 16.2262C17.2447 15.8525 17.1592 15.4224 16.8732 15.1364L16.166 14.4293V10.8435C16.166 7.52983 13.4798 4.84354 10.166 4.84354Z"
                            fill="#B3ADAD" />
                        <path
                            d="M10.166 20.8435C8.50916 20.8435 7.16602 19.5004 7.16602 17.8435H13.166C13.166 19.5004 11.8229 20.8435 10.166 20.8435Z"
                            fill="#B3ADAD" />
                        <circle cx="17.166" cy="5.84354" r="5" fill="#F44336" />
                    </svg>
                </a>
                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                    <li class="dropdown-menu-header">
                        <div class="dropdown-header d-flex">
                            <h4 class="notification-title mb-0 me-auto">Notifications</h4>
                            <div class="badge badge-dark rounded-pill new-notification-count">{{ $count_notifications }}
                                New</div>
                        </div>
                    </li>
                    <li class="scrollable-container media-list notification-block p-0">
                        @if ($count_notifications > 0)
                            @foreach ($notifications as $notification)
                                <a class="d-flex"
                                    href="{{ route('merchant-read-notifications', [$notification->id]) }}">
                                    <div class="list-item d-flex align-items-start">
                                        <div class="me-1">
                                            <div class="avatar bg-light-danger">
                                                <div class="avatar-content"><i data-feather="user"></i></div>
                                            </div>
                                        </div>
                                        <div class="list-item-body flex-grow-1">
                                            <p class="media-heading">
                                                <span class="fw-bolder">{{ $notification->title }}</span>
                                            </p>
                                            <small class="notification-text"> {{ $notification->body }}</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="text-center mt-1 mb-1">
                                No new notification
                            </div>
                        @endif
                    </li>
                    <li class="dropdown-menu-footer"><a class="btn btn-primary w-100"
                            href="{{ route('notifications') }}">Read all notifications</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown dropdown-user">
                <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="avatar">
                        <img class="round" src="{{ storage_asset('NewTheme/images/avatar7.png') }}" alt="avatar"
                            height="40" width="40">
                        <span class="avatar-status-online"></span>
                    </span>
                    <div class="user-nav d-sm-flex d-none">
                        <span class="user-name fw-bolder">{{ ucwords(Auth::guard('agentUser')->user()->name) }}</span>
                        <span class="user-status">RP</span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user" style="width: 14rem;">
                    <a class="dropdown-item" href="{{ route('profile-rp') }}">
                        <svg width="10" height="10" viewBox="0 0 19 19" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.48922 1.17094C9.1102 -0.390314 6.8898 -0.390314 6.51078 1.17094C6.26594 2.17949 5.11045 2.65811 4.22416 2.11809C2.85218 1.28212 1.28212 2.85218 2.11809 4.22416C2.65811 5.11045 2.17949 6.26593 1.17094 6.51078C-0.390314 6.8898 -0.390314 9.1102 1.17094 9.48922C2.17949 9.73407 2.65811 10.8896 2.11809 11.7758C1.28212 13.1478 2.85218 14.7179 4.22417 13.8819C5.11045 13.3419 6.26594 13.8205 6.51078 14.8291C6.8898 16.3903 9.1102 16.3903 9.48922 14.8291C9.73407 13.8205 10.8896 13.3419 11.7758 13.8819C13.1478 14.7179 14.7179 13.1478 13.8819 11.7758C13.3419 10.8896 13.8205 9.73407 14.8291 9.48922C16.3903 9.1102 16.3903 6.8898 14.8291 6.51078C13.8205 6.26593 13.3419 5.11045 13.8819 4.22416C14.7179 2.85218 13.1478 1.28212 11.7758 2.11809C10.8896 2.65811 9.73407 2.17949 9.48922 1.17094ZM8 11C9.65685 11 11 9.65685 11 8C11 6.34315 9.65685 5 8 5C6.34315 5 5 6.34315 5 8C5 9.65685 6.34315 11 8 11Z"
                                fill="#B3ADAD" />
                        </svg>
                        Edit Profile
                    </a>
                    <a class="dropdown-item" href="{!! URL::route('rp/logout') !!}" role="button">
                        <i class="fa fa-sign-out text-dark-1"></i>
                        Logout
                    </a>
                    {{-- <form id="logout-form" action="{{ route('rp/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form> --}}
                </div>
            </li>
        </ul>
        </li>
        </ul>
    </div>
</nav>






{{-- <div class="iq-top-navbar header-top-sticky">
    <div class="iq-navbar-custom">
        <div class="iq-sidebar-logo">
            <div class="top-logo">
                <a href="{{ route('rp.dashboard') }}" class="logo">
                    <img src="{{ storage_asset('ThemeFinvert/images/finvert.png') }}" class="img-fluid"
                        alt="">
                    <span><img src="{{ storage_asset('ThemeFinvert/images/finvert.png') }}" class="img-fluid"
                            alt=""></span>
                </a>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <div class="iq-search-bar">
                @yield('breadcrumbTitle')
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="ri-menu-3-line"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto navbar-list">
                    <li class="nav-item">
                        <div class="iq-menu-bt align-self-center">
                            <div class="wrapper-menu">
                                <div class="main-circle"><i class="ri-more-fill"></i></div>
                                <div class="hover-circle"><i class="ri-more-2-fill"></i></div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item iq-full-screen">
                        <a href="#" class="iq-waves-effect" id="btnFullscreen"><i
                                class="ri-fullscreen-line"></i></a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="search-toggle iq-waves-effect">
                            <i class="ri-notification-3-fill"></i>
                            <span class="bg-info dots"></span>
                        </a>
                        <div class="iq-sub-dropdown">
                            <div class="iq-card shadow-none m-0">
                                <div class="iq-card-body p-0 ">
                                    <div class="bg-primary p-3">
                                        <h5 class="mb-0 text-white">Notifications<small
                                                class="badge  badge-light float-right pt-1">{{ count(
                                                    getNotificationsForRP(
                                                        auth()->guard('agentUser')->user()->id,
                                                        'user',
                                                        5,
                                                    ),
                                                ) }}</small>
                                        </h5>
                                    </div>
                                    @if (count(
        getNotificationsForRP(
            auth()->guard('agentUser')->user()->id,
            'user',
            5,
        ),
    ) > 0)
                                        @foreach (getNotificationsForRP(
        auth()->guard('agentUser')->user()->id,
        'user',
        5,
    ) as $notification)
                                            <a href="{{ route('read-admin-notifications', [$notification->id]) }}"
                                                class="iq-sub-card">
                                                <div class="media align-items-center">
                                                    <div class="">
                                                        <img class="avatar-40 rounded"
                                                            src="{{ storage_asset('ThemeFinvert/images/user/01.jpg') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="media-body ml-3">
                                                        <h6 class="mb-0 ">{{ $notification->title }}</h6>
                                                        <small
                                                            class="font-size-12">{{ convertDateToLocal(
                                                                $notification->created_at,
                                                                'd-m-Y
                                                                                                                                                                                                                                                                                       / H:i:s',
                                                            ) }}</small>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                        <div class="d-inline-block w-100 text-center p-3">
                                            <a href="{{ route('notifications') }}">See all notifications</a>
                                        </div>
                                    @else
                                        <div class="text-left" style="padding: 0px 30px;">
                                            <p>No new notification</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <ul class="navbar-list">
                <li>
                    <a href="#" class="search-toggle iq-waves-effect d-flex align-items-center">
                        <img src="{{ storage_asset('ThemeFinvert/images/user/1.jpg') }}"
                            class="img-fluid rounded mr-3" alt="user">
                        <div class="caption">
                            <h6 class="mb-0 line-height">{{ ucwords(Auth::guard('agentUser')->user()->name) }}</h6>
                            <span class="font-size-12">Available</span>
                        </div>
                    </a>
                    <div class="iq-sub-dropdown iq-user-dropdown">
                        <div class="iq-card shadow-none m-0">
                            <div class="iq-card-body p-0 ">
                                <div class="bg-primary p-3">
                                    <h5 class="mb-0 text-white line-height">Hello
                                        {{ ucwords(Auth::guard('agentUser')->user()->name) }}</h5>
                                    <span class="text-white font-size-12">Available</span>
                                </div>
                                <a href="{!! route('profile-rp') !!}" class="iq-sub-card">
                                    <div class="media align-items-center">
                                        <div class="rounded iq-card-icon iq-bg-primary">
                                            <i class="ri-profile-line"></i>
                                        </div>
                                        <div class="media-body ml-3">
                                            <h6 class="mb-0 ">Edit Profile</h6>
                                            <p class="mb-0 font-size-12">Modify your personal details.</p>
                                        </div>
                                    </div>
                                </a>
                                <div class="d-inline-block w-100 text-center p-3">
                                    <a class="btn btn-primary" href="{!! URL::route('rp/logout') !!}" role="button"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Sign out<i class="ri-login-box-line ml-2"></i>
                                    </a>
                                    <form id="logout-form" action="{{ route('rp/logout') }}" method="GET"
                                        style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>

    </div>
</div> --}}
