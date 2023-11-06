<nav class="header-navbar navbar-expand-lg navbar navbar-fixed align-items-center navbar-shadow navbar-brand-center" data-nav="brand-center">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu ficon"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a></li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                       <img src="{{ storage_asset('NewTheme/images/white_logo.png') }}" class="logo-big">
                    </a>
                </li>
            </ul>
            <p class="font-size-16 mb-0">
                @yield('breadcrumbTitle')
            </p>
        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">
            <li class="nav-item dropdown dropdown-language">
                <div style="width: 400px;">
                    <span>
                        <b>Last Login Time &nbsp; : &nbsp; </b>
                        <span id="datetime" class="clock">Loading...</span>
                    </span>
                </div>
            </li>

            @if(\Auth::user()->is_white_label == '0')
            <li class="nav-item dropdown dropdown-notification">
                <a class="nav-link read-notification" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.166 4.84354C6.85234 4.84354 4.16604 7.52983 4.16604 10.8435V14.4293L3.45894 15.1364C3.17294 15.4224 3.08738 15.8525 3.24217 16.2262C3.39695 16.5999 3.76158 16.8435 4.16604 16.8435H16.166C16.5705 16.8435 16.9351 16.5999 17.0899 16.2262C17.2447 15.8525 17.1592 15.4224 16.8732 15.1364L16.166 14.4293V10.8435C16.166 7.52983 13.4798 4.84354 10.166 4.84354Z" fill="#FFFFFF"/>
                    <path d="M10.166 20.8435C8.50916 20.8435 7.16602 19.5004 7.16602 17.8435H13.166C13.166 19.5004 11.8229 20.8435 10.166 20.8435Z" fill="#FFFFFF"/>
                    <circle cx="17.166" cy="5.84354" r="5" fill="#F44336"/>
                    </svg>
                </a>
                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                    <li class="dropdown-menu-header">
                        <div class="dropdown-header d-flex">
                            <h4 class="notification-title mb-0 me-auto">Notifications</h4>
                            <div class="badge badge-dark rounded-pill new-notification-count">{{count($notifications)}} New</div>
                        </div>
                    </li>
                    <li class="scrollable-container media-list notification-block p-0">
                        @if(count($notifications) > 0)
                            @foreach ($notifications as $notification)
                                <a class="d-flex" href="{{ route('merchant-read-notifications',[$notification->id]) }}">
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
                    <li class="dropdown-menu-footer"><a class="btn btn-primary w-100" href="{{ route('merchant-notifications') }}">Read all notifications</a></li>
                </ul>
            </li>
            @endif
            <li class="nav-item dropdown dropdown-user">
                <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="avatar">
                        <img class="round" src="{{ storage_asset('NewTheme/images/avatar7.png')}}" alt="avatar" height="40" width="40">
                        <span class="avatar-status-online"></span>
                    </span>
                    <div class="user-nav d-sm-flex d-none">
                        <span class="user-name fw-bolder">{{ucwords(Auth::user()->name)}}</span>
                        <span class="user-status">Merchant</span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user" style="width: 14rem;">
                    <a class="dropdown-item" href="{{ route('setting') }}">
                        Edit Personal Info
                    </a>
                    <a class="dropdown-item" href="{!! URL::route('logout') !!}" role="button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                   </form>
                </div>
            </li>
        </ul>
    </div>
</nav>