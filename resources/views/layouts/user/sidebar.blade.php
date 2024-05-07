<!-- BEGIN: Main Menu-->
<div class="horizontal-menu-wrapper">
<div class="header-navbar navbar-expand-sm navbar navbar-horizontal floating-nav navbar-light navbar-shadow menu-border" role="navigation" data-menu="menu-wrapper" data-menu-type="floating-nav">
    <div class="navbar-container main-menu-content" data-menu="menu-container">
        <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">

            @if (\Auth::user()->is_white_label == '0')
                @if (!empty(Auth::user()->application))
                    @if (Auth::user()->application->status == 4 ||
                            Auth::user()->application->status == 5 ||
                            Auth::user()->application->status == 6 ||
                            Auth::user()->application->status == 10 ||
                            Auth::user()->application->status == 11)
                        <li class="{{ $pageActive == 'dashboard' ? 'active' : '' }} nav-item">
                            <a class="nav-link dropdown-item d-flex align-items-center" href="{{ route('dashboardPage') }}">
                                <div class="svg-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 20V14H14V20H19V12H22L12 3L2 12H5V20H10Z" class="hover-ch"/>
                                    </svg>
                                </div>
                                <span class="menu-title text-truncate" data-i18n="Home">Home</span>
                            </a>
                        </li>
                    @endif
                @endif
            @endif

            <li
                class="dropdown nav-item {{ $pageActive == 'my-application' || $pageActive == 'user-bank-details' || $pageActive == 'security-settings' ? 'sidebar-group-active open' : '' }}" data-menu="dropdown">
                <a href="#" class="dropdown-toggle nav-link dropdown-item d-flex align-items-center" data-bs-toggle="dropdown">
                    <div class="svg-icon">
                        <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.3333 13.2032C9.66325 13.2032 4.33325 14.5332 4.33325 17.2032V19.2032H20.3333V17.2032C20.3333 14.5332 15.0033 13.2032 12.3333 13.2032ZM12.3333 11.2032C13.3941 11.2032 14.4115 10.7818 15.1617 10.0317C15.9118 9.28153 16.3333 8.26411 16.3333 7.20325C16.3333 6.14238 15.9118 5.12497 15.1617 4.37482C14.4115 3.62467 13.3941 3.20325 12.3333 3.20325C11.2724 3.20325 10.255 3.62467 9.50482 4.37482C8.75468 5.12497 8.33325 6.14238 8.33325 7.20325C8.33325 8.26411 8.75468 9.28153 9.50482 10.0317C10.255 10.7818 11.2724 11.2032 12.3333 11.2032Z" class="hover-ch"/>
                        </svg>
                    </div>
                    <span class="menu-title text-truncate" data-i18n="Profile">Profile</span>
                </a>
                <ul class="dropdown-menu" data-bs-popper="none">
                    @if (Auth()->user()->main_user_id != '0')
                        @if (Auth()->user()->is_whitelable != '1')
                            @if (Auth()->user()->application_show == '1')
                                <?php
                                $c1 = '';
                                if ($pageActive == 'my-application' || $pageActive == 'start-my-application' || $pageActive == 'edit-my-application') {
                                    $c1 = 'active';
                                }
                                ?>
                                <li class="{{ $pageActive == 'my-application' ? 'active' : '' }}">
                                    <a class="dropdown-item dropdown-item d-flex align-items-center" href="{!! route('my-application') !!}">
                                        <span class="menu-item text-truncate" data-i18n="Profile Details">Profile Details</span>
                                    </a>
                                </li>
                            @endif
                        @endif
                    @else
                        @if (Auth()->user()->is_whitelable != '1')
                            <?php
                            $c1 = '';
                            if ($pageActive == 'my-application' || $pageActive == 'start-my-application' || $pageActive == 'edit-my-application') {
                                $c1 = 'active';
                            }
                            ?>
                            <li class="{{ $pageActive == 'my-application' ? 'active' : '' }}">
                                <a class="dropdown-item dropdown-item d-flex align-items-center" href="{!! route('my-application') !!}">
                                    <span class="menu-item text-truncate" data-i18n="Profile Details">Profile Details</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    @if (Auth()->user()->main_user_id == '0')
                        <li class="{{ $pageActive == 'user-bank-details' ? 'active' : '' }}">
                            <a class="dropdown-item dropdown-item d-flex align-items-center" href="{!! route('user.bank.details') !!}">
                                <span class="menu-item text-truncate" data-i18n="Bank Details">Bank Details</span>
                            </a>
                        </li>
                        <li class="{{ $pageActive == 'user-rates-fee' ? 'active' : '' }}">
                            <a class="dropdown-item dropdown-item d-flex align-items-center" href="{!! route('user.rates.fee') !!}">
                                <span class="menu-item text-truncate" data-i18n="Rates & Fee Details">Rates & Fee Details</span>
                            </a>
                        </li>
                    @else
                        @if (Auth()->user()->settings == '1')
                            <li class="{{ $pageActive == 'user-bank-details' ? 'active' : '' }}">
                                <a class="dropdown-item dropdown-item d-flex align-items-center" href="{!! route('user.bank.details') !!}">
                                    <span class="menu-item text-truncate" data-i18n="Bank Details">Bank Details</span>
                                </a>
                            </li>
                        @endif
                    @endif
                </ul>
            </li>

            @if (!empty(Auth::user()->application))
                @if (Auth::user()->application->status == 4 ||
                        Auth::user()->application->status == 5 ||
                        Auth::user()->application->status == 6 ||
                        Auth::user()->application->status == 10 ||
                        Auth::user()->application->status == 11)
                    <li
                        class="dropdown nav-item {{ $pageActive == 'transactions' ||
                        $pageActive == 'chargebacks' ||
                        $pageActive == 'refunds' ||
                        $pageActive == 'suspicious' ||
                        $pageActive == 'retrieval' ||
                        $pageActive == 'test-transactions'
                            ? 'sidebar-group-active open'
                            : '' }}">
                        <a class="dropdown-toggle nav-link dropdown-item d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                            <div class="svg-icon">
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.2666 11V8.6H5.46655V5.4H14.2666V3L19.8666 7L14.2666 11ZM5.46655 17L11.0666 21V18.6H19.8666V15.4H11.0666V13L5.46655 17Z" class="hover-ch"/>
                                </svg>
                            </div>
                            <span class="menu-title text-truncate" data-i18n="Transactions">Transactions</span>
                        </a>
                        <ul class="dropdown-menu" data-bs-popper="none">
                            <li class="{{ $pageActive == 'transactions' ? 'active' : '' }}">
                                <a class="dropdown-item d-flex align-items-center" href="{!! url('transactions') !!}">
                                    <span class="menu-title text-truncate" data-i18n="All Transactions">All
                                        Transactions</span>
                                </a>
                            </li>
                            <li class="{{ $pageActive == 'chargebacks' ? 'active' : '' }}">
                                <a class="dropdown-item d-flex align-items-center" href="{!! url('chargebacks') !!}">
                                    <span class="menu-title text-truncate" data-i18n="Chargebacks">Chargebacks</span>
                                </a>
                            </li>
                            <li class="{{ $pageActive == 'refunds' ? 'active' : '' }}">
                                <a class="dropdown-item d-flex align-items-center" href="{!! url('refunds') !!}">
                                    <span class="menu-title text-truncate" data-i18n="Refunds">Refunds</span>
                                </a>
                            </li>
                            <li class="{{ $pageActive == 'suspicious' ? 'active' : '' }}">
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('suspicious') }}">
                                    <span class="menu-title text-truncate" data-i18n="Marked Transactions">Marked
                                        Transactions</span>
                                </a>
                            </li>
                            <li class="{{ $pageActive == 'retrieval' ? 'active' : '' }}">
                                <a class="dropdown-item d-flex align-items-center" href="{!! url('retrieval') !!}">
                                    <span class="menu-title text-truncate" data-i18n="Retrievals">Retrievals</span>
                                </a>
                            </li>
                            <li class="{{ $pageActive == 'test-transactions' ? 'active' : '' }}">
                                <a class="dropdown-item d-flex align-items-center" href="{!! url('test-transactions') !!}">
                                    <span class="menu-title text-truncate" data-i18n="Test Transactions">Test
                                        Transactions</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="dropdown nav-item {{ $pageActive == 'payout-report' ||
                        $pageActive == 'transaction-volume' ||
                        $pageActive == 'risk-compliance-report' ||
                        $pageActive == 'payout-schedule'
                            ? 'sidebar-group-active open'
                            : '' }}">
                        <a class="dropdown-toggle nav-link dropdown-item d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                            <div class="svg-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.9998 9H18.4998L12.9998 3.5V9ZM5.99976 2H13.9998L19.9998 8V20C19.9998 20.5304 19.789 21.0391 19.414 21.4142C19.0389 21.7893 18.5302 22 17.9998 22H5.99976C4.88976 22 3.99976 21.1 3.99976 20V4C3.99976 2.89 4.88976 2 5.99976 2ZM6.99976 20H8.99976V14H6.99976V20ZM10.9998 20H12.9998V12H10.9998V20ZM14.9998 20H16.9998V16H14.9998V20Z" class="hover-ch"/>
                                </svg>
                            </div>
                            <span class="menu-title text-truncate" data-i18n="Reports">Reports</span>
                        </a>
                        <ul class="dropdown-menu" data-bs-popper="none">
                            <li class="{{ $pageActive == 'transaction-volume' ? 'active' : '' }}">
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('transaction-volume') }}">
                                    <span class="menu-title text-truncate" data-i18n="Transaction Summary">Transaction Summary</span>
                                </a>
                            </li>
                            <li class="{{ $pageActive == 'payout-report' ? 'active' : '' }}">
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('payout-report') }}">
                                    <span class="menu-title text-truncate" data-i18n="Settlement">Settlement</span>
                                </a>
                            </li>
                            @if (!empty(Auth::user()->application))
                                @if (Auth::user()->application->status == 4 ||
                                        Auth::user()->application->status == 5 ||
                                        Auth::user()->application->status == 6)
                                    <li class="{{ $pageActive == 'payout-schedule' ? 'active' : '' }}">
                                        <a class="dropdown-item d-flex align-items-center" href="{!! url('payout-schedule') !!}">
                                            <span class="menu-title text-truncate"
                                                data-i18n="Settlement Schedule">Settlement Schedule</span>
                                        </a>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </li>
                @endif
            @elseif(Auth()->user()->main_user_id != '0')
                @if (Auth()->user()->transactions == '1')
                    <li
                        class="dropdown nav-item {{ $pageActive == 'transactions' ||
                        $pageActive == 'chargebacks' ||
                        $pageActive == 'refunds' ||
                        $pageActive == 'suspicious' ||
                        $pageActive == 'retrieval' ||
                        $pageActive == 'test-transactions'
                            ? 'sidebar-group-active open'
                            : '' }}">
                        <a class="dropdown-toggle nav-link dropdown-item d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                            <div class="svg-icon">
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.2666 11V8.6H5.46655V5.4H14.2666V3L19.8666 7L14.2666 11ZM5.46655 17L11.0666 21V18.6H19.8666V15.4H11.0666V13L5.46655 17Z" class="hover-ch"/>
                                </svg>
                            </div>
                            <span class="menu-title text-truncate" data-i18n="Transaction">Transaction</span>
                        </a>
                        <ul class="dropdown-menu" data-bs-popper="none">
                            <li class="{{ $pageActive == 'transactions' ? 'active' : '' }}">
                                <a class="dropdown-item d-flex align-items-center" href="{!! url('transactions') !!}">
                                    <span class="menu-title text-truncate" data-i18n="All Transactions">All
                                        Transactions</span>
                                </a>
                            </li>
                            <li class="{{ $pageActive == 'chargebacks' ? 'active' : '' }}">
                                <a class="dropdown-item d-flex align-items-center" href="{!! url('chargebacks') !!}">
                                    <span class="menu-title text-truncate" data-i18n="Chargebacks">Chargebacks</span>
                                </a>
                            </li>
                            <li class="{{ $pageActive == 'refunds' ? 'active' : '' }}">
                                <a class="dropdown-item d-flex align-items-center" href="{!! url('refunds') !!}">
                                    <span class="menu-title text-truncate" data-i18n="Refunds">Refunds</span>
                                </a>
                            </li>
                            <li class="{{ $pageActive == 'suspicious' ? 'active' : '' }}">
                                <a class="dropdown-item d-flex align-items-center" href="{!! url('suspicious') !!}">
                                    <span class="menu-title text-truncate" data-i18n="Flagged">Flagged</span>
                                </a>
                            </li>
                            <li class="{{ $pageActive == 'retrieval' ? 'active' : '' }}">
                                <a class="dropdown-item d-flex align-items-center" href="{!! url('retrieval') !!}">
                                    <span class="menu-title text-truncate" data-i18n="Retrievals">Retrievals</span>
                                </a>
                            </li>
                            <li class="{{ $pageActive == 'test-transactions' ? 'active' : '' }}">
                                <a class="dropdown-item d-flex align-items-center" href="{!! url('test-transactions') !!}">
                                    <span class="menu-title text-truncate" data-i18n="Test Transactions">Test
                                        Transactions</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (Auth()->user()->reports == '1')
                    <li
                        class="dropdown nav-item {{ $pageActive == 'payout-schedule' || $pageActive == 'risk-compliance-report' || $pageActive == 'transaction-volume' ? 'sidebar-group-active open' : '' }}">
                        <a class="dropdown-toggle nav-link dropdown-item d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                            <div class="svg-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.9998 9H18.4998L12.9998 3.5V9ZM5.99976 2H13.9998L19.9998 8V20C19.9998 20.5304 19.789 21.0391 19.414 21.4142C19.0389 21.7893 18.5302 22 17.9998 22H5.99976C4.88976 22 3.99976 21.1 3.99976 20V4C3.99976 2.89 4.88976 2 5.99976 2ZM6.99976 20H8.99976V14H6.99976V20ZM10.9998 20H12.9998V12H10.9998V20ZM14.9998 20H16.9998V16H14.9998V20Z" class="hover-ch"/>
                                </svg>
                            </div>
                            <span class="menu-title text-truncate" data-i18n="Reports">Reports</span>
                        </a>
                        <ul class="dropdown-menu" data-bs-popper="none">
                            <li class="{{ $pageActive == 'transaction-volume' ? 'active' : '' }}">
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('transaction-volume') }}">
                                    <span class="menu-title text-truncate" data-i18n="Transaction Summary">Transaction Summary</span>
                                </a>
                            </li>
                            <li class="{{ $pageActive == 'payout-report' ? 'active' : '' }}">
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('payout-report') }}">
                                    <span class="menu-title text-truncate" data-i18n="Payout Reports">Payout
                                        Reports</span>
                                </a>
                            </li>
                            @if (!empty(Auth::user()->application))
                                @if (Auth::user()->application->status == 4 ||
                                        Auth::user()->application->status == 5 ||
                                        Auth::user()->application->status == 6)
                                    <li class="{{ $pageActive == 'payout-schedule' ? 'active' : '' }}">
                                        <a class="dropdown-item d-flex align-items-center" href="{!! url('payout-schedule') !!}">
                                            <span class="menu-title text-truncate"
                                                data-i18n="Settlement Schedule">Settlement Schedule</span>
                                        </a>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </li>
                @endif
            @endif

            @if (\Auth::user()->is_white_label == '1')
                <li
                    class="dropdown nav-item {{ $pageActive == 'transactions' || $pageActive == 'test-transactions' ? 'sidebar-group-active open' : '' }}">
                    <a class="dropdown-toggle nav-link dropdown-item d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                        <div class="svg-icon">
                            <svg width="20" height="24" viewBox="0 0 20 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M16.6202 3.18634L14.7628 1.32891L15.8878 0.203888L19.6658 3.98185L15.8878 7.75982L14.7628 6.63479L16.6202 4.77737H6.71202C5.10106 4.77737 3.79513 6.0833 3.79513 7.69425V9.30041H2.2041V7.69425C2.2041 5.2046 4.22236 3.18634 6.71202 3.18634H16.6202ZM13.858 18.5178C15.469 18.5178 16.7749 17.2119 16.7749 15.6009V13.9948H18.366V15.6009C18.366 18.0906 16.3477 20.1088 13.858 20.1088H3.94986L5.80728 21.9662L4.68225 23.0913L0.904297 19.3133L4.68226 15.5353L5.80729 16.6604L3.94986 18.5178H13.858ZM7.75 7.75C6.5163 7.75 5.625 8.82012 5.625 10V14C5.625 15.1799 6.5163 16.25 7.75 16.25H13.25C14.4837 16.25 15.375 15.1799 15.375 14V10C15.375 8.82012 14.4837 7.75 13.25 7.75H7.75ZM7.125 14V11.75H13.875V14C13.875 14.477 13.5351 14.75 13.25 14.75H7.75C7.46492 14.75 7.125 14.477 7.125 14ZM7.125 10.25H13.875V10C13.875 9.52302 13.5351 9.25 13.25 9.25H7.75C7.46492 9.25 7.125 9.52302 7.125 10V10.25ZM8.20833 12.75C7.79412 12.75 7.45833 13.0858 7.45833 13.5C7.45833 13.9142 7.79412 14.25 8.20833 14.25H8.66667C9.08088 14.25 9.41667 13.9142 9.41667 13.5C9.41667 13.0858 9.08088 12.75 8.66667 12.75H8.20833ZM10.5 12.75C10.0858 12.75 9.75 13.0858 9.75 13.5C9.75 13.9142 10.0858 14.25 10.5 14.25H10.9583C11.3725 14.25 11.7083 13.9142 11.7083 13.5C11.7083 13.0858 11.3725 12.75 10.9583 12.75H10.5Z"
                                    class="hover-ch" />
                            </svg>
                        </div>
                        <span class="menu-title text-truncate" data-i18n="Transactions">Transactions</span>
                    </a>
                    <ul class="dropdown-menu" data-bs-popper="none">
                        <li class="{{ $pageActive == 'transactions' ? 'active' : '' }}">
                            <a class="dropdown-item d-flex align-items-center" href="{!! url('transactions') !!}">
                                <span class="menu-title text-truncate" data-i18n="All Transactions">All
                                    Transactions</span>
                            </a>
                        </li>
                        <li class="{{ $pageActive == 'test-transactions' ? 'active' : '' }}">
                            <a class="dropdown-item d-flex align-items-center" href="{!! url('test-transactions') !!}">
                                <span class="menu-title text-truncate" data-i18n="Test Transactions">Test
                                    Transactions</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            <li
                class="dropdown nav-item {{ $pageActive == 'whitelist-ip' || $pageActive == 'iframe' || $pageActive == 'api-document' ? 'sidebar-group-active open' : '' }}">
                <a class="dropdown-toggle nav-link dropdown-item d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                    <div class="svg-icon">
                        <svg width="20" height="18" viewBox="0 0 17 17" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M10.2583 1.41762C9.87924 -0.14364 7.65885 -0.143641 7.27982 1.41761C7.03498 2.42617 5.87949 2.90478 4.99321 2.36476C3.62122 1.5288 2.05117 3.09885 2.88713 4.47084C3.42715 5.35712 2.94854 6.51261 1.93998 6.75745C0.378729 7.13648 0.378729 9.35687 1.93998 9.7359C2.94854 9.98074 3.42715 11.1362 2.88713 12.0225C2.05117 13.3945 3.62122 14.9646 4.99321 14.1286C5.87949 13.5886 7.03498 14.0672 7.27982 15.0757C7.65885 16.637 9.87924 16.637 10.2583 15.0757C10.5031 14.0672 11.6586 13.5886 12.5449 14.1286C13.9169 14.9646 15.4869 13.3945 14.651 12.0225C14.1109 11.1362 14.5896 9.98074 15.5981 9.7359C17.1594 9.35687 17.1594 7.13648 15.5981 6.75745C14.5896 6.51261 14.1109 5.35712 14.651 4.47084C15.4869 3.09885 13.9169 1.5288 12.5449 2.36476C11.6586 2.90478 10.5031 2.42617 10.2583 1.41762ZM8.76904 11.2467C10.4259 11.2467 11.769 9.90353 11.769 8.24667C11.769 6.58982 10.4259 5.24667 8.76904 5.24667C7.11219 5.24667 5.76904 6.58982 5.76904 8.24667C5.76904 9.90353 7.11219 11.2467 8.76904 11.2467Z"
                                class="hover-ch" />
                        </svg>
                    </div>
                    <span class="menu-title text-truncate" data-i18n="Technical Settings">Technical Settings</span>
                </a>
                <ul class="dropdown-menu" data-bs-popper="none">
                    <li class="{{ $pageActive == 'whitelist-ip' ? 'active' : '' }}">
                        <a class="dropdown-item d-flex align-items-center" href="{!! url('whitelist-ip') !!}">
                            <span class="menu-title text-truncate" data-i18n="IP Whitelist Request">IP Whitelist Request</span>
                        </a>
                    </li>
                    <li class="{{ $pageActive == 'iframe' ? 'active' : '' }}">
                        <a class="dropdown-item d-flex align-items-center" href="{!! route('iframe') !!}">
                            <span class="menu-title text-truncate" data-i18n="Generate Payment Link">Generate Payment Link</span>
                        </a>
                    </li>
                    <li class="{{ $pageActive == 'api-document' ? 'active' : '' }}">
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('api-document') }}">
                            <span class="menu-title text-truncate" data-i18n="API Documentation">API Documentation</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="{{ $pageActive == 'security-settings' ? 'active' : '' }} nav-item">
                <a class="nav-link dropdown-item d-flex align-items-center" href="{{ route('security-settings') }}">
                    <div class="svg-icon">
                        <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.6663 12H19.6663C19.1363 16.11 16.3863 19.78 12.6663 20.92V12H5.66626V6.3L12.6663 3.19V12ZM12.6663 1L3.66626 5V11C3.66626 16.55 7.50626 21.73 12.6663 23C17.8263 21.73 21.6663 16.55 21.6663 11V5L12.6663 1Z" class="hover-ch"/>
                        </svg>
                    </div>
                    <span class="menu-title text-truncate" data-i18n="Security">Security</span>
                </a>
            </li>

            <li
                class="dropdown nav-item {{ $pageActive == 'ticket' || $pageActive == 'faq' ? 'sidebar-group-active open' : '' }}">
                <a class="dropdown-toggle nav-link dropdown-item d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                    <div class="svg-icon">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_23_66)">
                        <path d="M12.0698 2.46997C9.94809 2.46997 7.91326 3.31283 6.41297 4.81312C4.91268 6.31341 4.06982 8.34824 4.06982 10.47V12.37C3.76816 12.6328 3.52373 12.9549 3.35171 13.3161C3.17968 13.6774 3.08374 14.0701 3.06982 14.47C3.0926 15.0498 3.29001 15.6093 3.63619 16.075C3.98236 16.5407 4.46115 16.891 5.00982 17.08C6.30982 20.19 8.91982 22.47 12.0698 22.47H15.0698V20.47H12.0698C9.80982 20.47 7.75982 18.77 6.72982 16.08L6.51982 15.53L5.92982 15.47C5.68955 15.436 5.46982 15.3158 5.31159 15.1318C5.15336 14.9479 5.06744 14.7126 5.06982 14.47C5.07088 14.2955 5.11758 14.1243 5.2053 13.9734C5.29301 13.8225 5.41869 13.6972 5.56982 13.61L6.06982 13.32V11.47C6.06982 11.2048 6.17518 10.9504 6.36272 10.7629C6.55025 10.5753 6.80461 10.47 7.06982 10.47H17.0698C17.335 10.47 17.5894 10.5753 17.7769 10.7629C17.9645 10.9504 18.0698 11.2048 18.0698 11.47V16.47H13.9798C13.889 16.2154 13.7312 15.9901 13.5229 15.8178C13.3146 15.6456 13.0636 15.5328 12.7966 15.4915C12.5295 15.4501 12.2562 15.4817 12.0055 15.5828C11.7549 15.684 11.5363 15.851 11.3727 16.0662C11.2092 16.2814 11.1068 16.5367 11.0765 16.8053C11.0461 17.0739 11.0889 17.3456 11.2002 17.5919C11.3116 17.8382 11.4874 18.0498 11.7092 18.2043C11.9309 18.3589 12.1902 18.4507 12.4598 18.47H20.0698C20.6003 18.47 21.109 18.2593 21.484 17.8842C21.8591 17.5091 22.0698 17.0004 22.0698 16.47V14.47C22.0698 13.9395 21.8591 13.4308 21.484 13.0558C21.109 12.6807 20.6003 12.47 20.0698 12.47V10.47C20.0698 8.34824 19.227 6.31341 17.7267 4.81312C16.2264 3.31283 14.1916 2.46997 12.0698 2.46997Z" class="hover-ch"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_23_66">
                        <rect width="24" height="24" fill="white" transform="translate(0.0698242 0.469971)"/>
                        </clipPath>
                        </defs>
                        </svg>
                    </div>
                    <span class="menu-title text-truncate" data-i18n="Support">Support</span>
                </a>
                <ul class="dropdown-menu" data-bs-popper="none">
                    <li class="{{ $pageActive == 'ticket' ? 'active' : '' }}">
                        <a class="dropdown-item d-flex align-items-center" href="{!! url('ticket') !!}">
                            <span class="menu-title text-truncate" data-i18n="Tickets">Tickets</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- <li class="{{ $pageActive == 'trade' ? 'active' : '' }} nav-item">
                <a class="nav-link dropdown-item d-flex align-items-center" href="{{ route('trade.index') }}">
                     <div class="svg-icon">
                        <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.2666 11V8.6H5.46655V5.4H14.2666V3L19.8666 7L14.2666 11ZM5.46655 17L11.0666 21V18.6H19.8666V15.4H11.0666V13L5.46655 17Z" class="hover-ch"/>
                        </svg>
                    </div>
                    <span class="menu-title text-truncate" data-i18n="Trade">Trade</span>
                </a>
            </li> --}}
        </ul>
    </div>
</div>
</div>
<!-- END: Main Menu-->
