<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
                <a class="navbar-brand" href="#">
                    <img src="{{ storage_asset('NewTheme/images/logo_sm.png') }}" class="logo-sm">
                    <img src="{{ storage_asset('NewTheme/images/logo.png') }}" class="logo-big">
                </a>
            </li>
            <li class="nav-item nav-toggle mr-10">
                <a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse">
                    <svg width="16" height="5" viewBox="0 0 16 5" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M4 2.84354C4 3.94811 3.10457 4.84354 2 4.84354C0.895431 4.84354 0 3.94811 0 2.84354C0 1.73897 0.895431 0.843536 2 0.843536C3.10457 0.843536 4 1.73897 4 2.84354Z"
                            fill="#7D7D7D" />
                        <path
                            d="M10 2.84354C10 3.94811 9.10457 4.84354 8 4.84354C6.89543 4.84354 6 3.94811 6 2.84354C6 1.73897 6.89543 0.843536 8 0.843536C9.10457 0.843536 10 1.73897 10 2.84354Z"
                            fill="#7D7D7D" />
                        <path
                            d="M14 4.84354C15.1046 4.84354 16 3.94811 16 2.84354C16 1.73897 15.1046 0.843536 14 0.843536C12.8954 0.843536 12 1.73897 12 2.84354C12 3.94811 12.8954 4.84354 14 4.84354Z"
                            fill="#7D7D7D" />
                    </svg>
                </a>
            </li>
        </ul>
    </div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            @if (RpApplicationStatus(auth()->guard('agentUser')->user()->id) == '1')
                <li class="{{ $pageActive == 'dashboard' ? 'active' : '' }} nav-item">
                    <a href="{{ route('rp.dashboard') }}" class="d-flex align-items-center">
                        <div class="svg-icon">
                            <svg width="20" height="20" viewBox="0 0 17 17" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.47615 0.539567C9.08563 0.149042 8.45246 0.149042 8.06194 0.539567L1.06194 7.53957C0.671412 7.93009 0.671412 8.56326 1.06194 8.95378C1.45246 9.34431 2.08563 9.34431 2.47615 8.95378L2.76904 8.66089V15.2467C2.76904 15.799 3.21676 16.2467 3.76904 16.2467H5.76904C6.32133 16.2467 6.76904 15.799 6.76904 15.2467V13.2467C6.76904 12.6944 7.21676 12.2467 7.76904 12.2467H9.76904C10.3213 12.2467 10.769 12.6944 10.769 13.2467V15.2467C10.769 15.799 11.2168 16.2467 11.769 16.2467H13.769C14.3213 16.2467 14.769 15.799 14.769 15.2467V8.66089L15.0619 8.95378C15.4525 9.34431 16.0856 9.34431 16.4761 8.95378C16.8667 8.56326 16.8667 7.93009 16.4761 7.53957L9.47615 0.539567Z"
                                    class="hover-ch" />
                            </svg>
                        </div>
                        <span class="menu-title text-truncate ps-1" data-i18n="Overview">Overview</span>

                    </a>
                </li>
                <li class="{{ $pageActive == 'my-application' ? 'active' : '' }} nav-item">
                    <a href="{{ route('rp.my-application.detail') }}" class="d-flex align-items-center">
                        <div class="svg-icon">
                            <svg width="20" height="21" viewBox="0 0 20 21" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17 19.2467V3.24667C17 2.1421 16.1046 1.24667 15 1.24667H5C3.89543 1.24667 3 2.1421 3 3.24667V19.2467M17 19.2467L19 19.2466M17 19.2467H12M3 19.2467L1 19.2466M3 19.2467H8M7 5.24665H8M7 9.24665H8M12 5.24665H13M12 9.24665H13M8 19.2467V14.2467C8 13.6944 8.44772 13.2467 9 13.2467H11C11.5523 13.2467 12 13.6944 12 14.2467V19.2467M8 19.2467H12"
                                    stroke="#B3ADAD" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="hover-ch" />
                            </svg>
                        </div>
                        <span class="menu-title text-truncate ps-1" data-i18n="Aplication">My Application</span>
                    </a>
                </li>
                <li class="{{ $pageActive == 'bank-details' ? 'active' : '' }} nav-item">
                    <a href="{{ route('agent.bank.details') }}" class="d-flex align-items-center">
                        <div class="svg-icon">
                            <svg width="21" height="20" viewBox="0 0 21 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M6.03052 12V15M10.0305 12V15M14.0305 12V15M1.03052 19H19.0305M1.03052 8H19.0305M1.03052 5L10.0305 1L19.0305 5M2.03052 8H18.0305V19H2.03052V8Z"
                                    stroke="#D9D9D9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="hover-ch" />
                            </svg>
                        </div>
                        <span class="menu-title text-truncate ps-1" data-i18n="Bank">Bank Details</span>
                    </a>
                </li>
                <li
                    class="{{ $pageActive == 'user-management' || $pageActive == 'user-management-application-show' || $pageActive == 'user-management-application-edit' || $pageActive == 'rp-merchant-payout-report' ? 'active' : '' }} nav-item">
                    <a href="{{ route('rp.user-management') }}" class="d-flex align-items-center">
                        <div class="svg-icon">
                            <svg width="18" height="15" viewBox="0 0 18 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8 3.24667C8 4.90353 6.65685 6.24667 5 6.24667C3.34315 6.24667 2 4.90353 2 3.24667C2 1.58982 3.34315 0.246674 5 0.246674C6.65685 0.246674 8 1.58982 8 3.24667Z"
                                    fill="#B3ADAD" class="hover-ch" />
                                <path
                                    d="M16 3.24667C16 4.90353 14.6569 6.24667 13 6.24667C11.3431 6.24667 10 4.90353 10 3.24667C10 1.58982 11.3431 0.246674 13 0.246674C14.6569 0.246674 16 1.58982 16 3.24667Z"
                                    fill="#B3ADAD" class="hover-ch" />
                                <path
                                    d="M11.9291 14.2467C11.9758 13.9201 12 13.5862 12 13.2467C12 11.6115 11.4393 10.1073 10.4998 8.91574C11.2352 8.49022 12.0892 8.24667 13 8.24667C15.7614 8.24667 18 10.4852 18 13.2467V14.2467H11.9291Z"
                                    fill="#B3ADAD" class="hover-ch" />
                                <path
                                    d="M5 8.24667C7.76142 8.24667 10 10.4852 10 13.2467V14.2467H0V13.2467C0 10.4852 2.23858 8.24667 5 8.24667Z"
                                    fill="#B3ADAD" class="hover-ch" />
                            </svg>

                        </div>
                        <span class="menu-title text-truncate ps-1" data-i18n="MerchantMangement"> Merchants
                            Management</span>
                    </a>
                </li>
                @if (Auth::guard('agentUser')->user()->main_agent_id == 0)
                    <li class="{{ $pageActive == 'sub-rp' ? 'active' : '' }} nav-item">
                        <a href="{{ route('sub-rp.index') }}" class="d-flex align-items-center">
                            <div class="svg-icon">
                                <svg width="18" height="15" viewBox="0 0 18 15" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8 3.24667C8 4.90353 6.65685 6.24667 5 6.24667C3.34315 6.24667 2 4.90353 2 3.24667C2 1.58982 3.34315 0.246674 5 0.246674C6.65685 0.246674 8 1.58982 8 3.24667Z"
                                        fill="#B3ADAD" class="hover-ch" />
                                    <path
                                        d="M16 3.24667C16 4.90353 14.6569 6.24667 13 6.24667C11.3431 6.24667 10 4.90353 10 3.24667C10 1.58982 11.3431 0.246674 13 0.246674C14.6569 0.246674 16 1.58982 16 3.24667Z"
                                        fill="#B3ADAD" class="hover-ch" />
                                    <path
                                        d="M11.9291 14.2467C11.9758 13.9201 12 13.5862 12 13.2467C12 11.6115 11.4393 10.1073 10.4998 8.91574C11.2352 8.49022 12.0892 8.24667 13 8.24667C15.7614 8.24667 18 10.4852 18 13.2467V14.2467H11.9291Z"
                                        fill="#B3ADAD" class="hover-ch" />
                                    <path
                                        d="M5 8.24667C7.76142 8.24667 10 10.4852 10 13.2467V14.2467H0V13.2467C0 10.4852 2.23858 8.24667 5 8.24667Z"
                                        fill="#B3ADAD" class="hover-ch" />
                                </svg>

                            </div>

                            <span class="menu-title text-truncate ps-1">Sub User Management</span>
                        </a>
                    </li>
                @endif
                <li
                    class="nav-item has-sub {{ $pageActive == 'merchant-transactions' ||
                    $pageActive == 'merchant-chargebacks-transactions' ||
                    $pageActive == 'merchant-refund-transactions' ||
                    $pageActive == 'merchant-marked-transactions' ||
                    $pageActive == 'merchant-retrieval-transactions' ||
                    $pageActive == 'rp-merchant-transactions-show'
                        ? 'active'
                        : '' }}">
                    <a href="#Transaction" class="d-flex align-items-center collapsed">
                        <div class="svg-icon">
                            <svg width="20" height="17" viewBox="0 0 20 17" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1 6.24667H19M5 11.2467H6M10 11.2467H11M4 15.2467H16C17.6569 15.2467 19 13.9035 19 12.2467V4.24667C19 2.58982 17.6569 1.24667 16 1.24667H4C2.34315 1.24667 1 2.58982 1 4.24667V12.2467C1 13.9035 2.34315 15.2467 4 15.2467Z"
                                    stroke="#B3ADAD" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="hover-ch" />
                            </svg>

                        </div>
                        <span class="menu-title text-truncate ps-1">Transactions</span>
                    </a>
                    <ul class="menu-content">
                        <li class="{{ $pageActive == 'merchant-transactions' ? 'active' : '' }}">
                            <a href="{{ route('rp-merchant-transactions') }}" class="d-flex align-items-center">
                                <svg width="7" height="11" viewBox="0 0 7 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.378831 10.5282C-0.0116932 10.1376 -0.0116932 9.50446 0.378831 9.11394L3.67172 5.82104L0.378831 2.52815C-0.0116936 2.13763 -0.0116936 1.50446 0.378831 1.11394C0.769355 0.723414 1.40252 0.723414 1.79304 1.11394L5.79304 5.11394C6.18357 5.50446 6.18357 6.13763 5.79304 6.52815L1.79304 10.5282C1.40252 10.9187 0.769355 10.9187 0.378831 10.5282Z"
                                        fill="#B3ADAD" />
                                </svg>
                                <span class="menu-item text-truncate ps-1">All Transactions</span>
                            </a>
                        </li>
                        <li class="{{ $pageActive == 'merchant-refund-transactions' ? 'active' : '' }}">
                            <a href="{{ route('rp-merchant-refund-transactions') }}"
                                class="d-flex align-items-center">
                                <svg width="7" height="11" viewBox="0 0 7 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.378831 10.5282C-0.0116932 10.1376 -0.0116932 9.50446 0.378831 9.11394L3.67172 5.82104L0.378831 2.52815C-0.0116936 2.13763 -0.0116936 1.50446 0.378831 1.11394C0.769355 0.723414 1.40252 0.723414 1.79304 1.11394L5.79304 5.11394C6.18357 5.50446 6.18357 6.13763 5.79304 6.52815L1.79304 10.5282C1.40252 10.9187 0.769355 10.9187 0.378831 10.5282Z"
                                        fill="#B3ADAD" />
                                </svg>
                                <span class="menu-item text-truncate ps-1">Refunds</span>
                            </a>
                        </li>
                        <li class="{{ $pageActive == 'merchant-chargebacks-transactions' ? 'active' : '' }}"><a
                                class="d-flex align-items-center"
                                href="{{ route('rp-merchant-chargebacks-transactions') }}">
                                <svg width="7" height="11" viewBox="0 0 7 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.378831 10.5282C-0.0116932 10.1376 -0.0116932 9.50446 0.378831 9.11394L3.67172 5.82104L0.378831 2.52815C-0.0116936 2.13763 -0.0116936 1.50446 0.378831 1.11394C0.769355 0.723414 1.40252 0.723414 1.79304 1.11394L5.79304 5.11394C6.18357 5.50446 6.18357 6.13763 5.79304 6.52815L1.79304 10.5282C1.40252 10.9187 0.769355 10.9187 0.378831 10.5282Z"
                                        fill="#B3ADAD" />
                                </svg>
                                <span class="menu-item text-truncate ps-1">Chargebacks</span>
                            </a></li>
                        <li class="{{ $pageActive == 'merchant-retrieval-transactions' ? 'active' : '' }}"><a
                                class="d-flex align-items-center"
                                href="{{ route('rp-merchant-retrieval-transactions') }}">
                                <svg width="7" height="11" viewBox="0 0 7 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.378831 10.5282C-0.0116932 10.1376 -0.0116932 9.50446 0.378831 9.11394L3.67172 5.82104L0.378831 2.52815C-0.0116936 2.13763 -0.0116936 1.50446 0.378831 1.11394C0.769355 0.723414 1.40252 0.723414 1.79304 1.11394L5.79304 5.11394C6.18357 5.50446 6.18357 6.13763 5.79304 6.52815L1.79304 10.5282C1.40252 10.9187 0.769355 10.9187 0.378831 10.5282Z"
                                        fill="#B3ADAD" />
                                </svg>
                                <span class="menu-item text-truncate ps-1">Retrieval</span>
                            </a></li>
                        <li class="{{ $pageActive == 'merchant-marked-transactions' ? 'active' : '' }}"><a
                                class="d-flex align-items-center"
                                href="{{ route('rp-merchant-suspicious-transactions') }}">
                                <svg width="7" height="11" viewBox="0 0 7 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.378831 10.5282C-0.0116932 10.1376 -0.0116932 9.50446 0.378831 9.11394L3.67172 5.82104L0.378831 2.52815C-0.0116936 2.13763 -0.0116936 1.50446 0.378831 1.11394C0.769355 0.723414 1.40252 0.723414 1.79304 1.11394L5.79304 5.11394C6.18357 5.50446 6.18357 6.13763 5.79304 6.52815L1.79304 10.5282C1.40252 10.9187 0.769355 10.9187 0.378831 10.5282Z"
                                        fill="#B3ADAD" />
                                </svg>
                                <span class="menu-item text-truncate ps-1">Marked Transactions</span>
                            </a></li>
                    </ul>
                </li>
                <!-- Reports Side menu -->
                <li
                    class="nav-item has-sub {{ $pageActive == 'payout-report' || $pageActive == 'rp-merchant-transaction-report' || $pageActive == 'rp-commision-report' || $pageActive == 'risk-report' ? 'active' : '' }}">
                    <a href="#" class="d-flex align-items-center">
                        <div class="svg-icon">
                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M0 8.24667C0 3.8284 3.58172 0.246674 8 0.246674V8.24667H16C16 12.665 12.4183 16.2467 8 16.2467C3.58172 16.2467 0 12.665 0 8.24667Z"
                                    fill="#B3ADAD" class="hover-ch" />
                                <path d="M10 0.498627C12.8113 1.22219 15.0245 3.43544 15.748 6.24672H10V0.498627Z"
                                    fill="#B3ADAD" class="hover-ch" />
                            </svg>
                        </div>
                        <span class="menu-title text-truncate ps-1" data-i18n="reports">Reports</span>
                    </a>
                    <ul class="menu-content">
                        <li class="{{ $pageActive == 'rp-merchant-transaction-report' ? 'active' : '' }}"><a
                                class="d-flex align-items-center"
                                href="{{ route('rp.merchant-transaction-report') }}">
                                <svg width="7" height="11" viewBox="0 0 7 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.378831 10.5282C-0.0116932 10.1376 -0.0116932 9.50446 0.378831 9.11394L3.67172 5.82104L0.378831 2.52815C-0.0116936 2.13763 -0.0116936 1.50446 0.378831 1.11394C0.769355 0.723414 1.40252 0.723414 1.79304 1.11394L5.79304 5.11394C6.18357 5.50446 6.18357 6.13763 5.79304 6.52815L1.79304 10.5282C1.40252 10.9187 0.769355 10.9187 0.378831 10.5282Z"
                                        fill="#B3ADAD" />
                                </svg>
                                <span class="menu-title text-truncate ps-1">Transaction Summary</span>
                            </a></li>
                        <li class="{{ $pageActive == 'risk-report' ? 'active' : '' }}"><a
                                class="d-flex align-items-center" href="{{ route('rp.risk-report') }}">
                                <svg width="7" height="11" viewBox="0 0 7 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.378831 10.5282C-0.0116932 10.1376 -0.0116932 9.50446 0.378831 9.11394L3.67172 5.82104L0.378831 2.52815C-0.0116936 2.13763 -0.0116936 1.50446 0.378831 1.11394C0.769355 0.723414 1.40252 0.723414 1.79304 1.11394L5.79304 5.11394C6.18357 5.50446 6.18357 6.13763 5.79304 6.52815L1.79304 10.5282C1.40252 10.9187 0.769355 10.9187 0.378831 10.5282Z"
                                        fill="#B3ADAD" />
                                </svg>
                                <span class="menu-title text-truncate ps-1"> Risk
                                    Report</span>
                            </a></li>
                        <li class="{{ $pageActive == 'rp-commision-report' ? 'active' : '' }}"><a
                                class="d-flex align-items-center" href="{{ route('rp.commision-report') }}">
                                <svg width="7" height="11" viewBox="0 0 7 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.378831 10.5282C-0.0116932 10.1376 -0.0116932 9.50446 0.378831 9.11394L3.67172 5.82104L0.378831 2.52815C-0.0116936 2.13763 -0.0116936 1.50446 0.378831 1.11394C0.769355 0.723414 1.40252 0.723414 1.79304 1.11394L5.79304 5.11394C6.18357 5.50446 6.18357 6.13763 5.79304 6.52815L1.79304 10.5282C1.40252 10.9187 0.769355 10.9187 0.378831 10.5282Z"
                                        fill="#B3ADAD" />
                                </svg>
                                <span class="menu-title text-truncate ps-1">
                                    Commision Report</span>
                            </a></li>
                        <li class="{{ $pageActive == 'payout-report' ? 'active' : '' }}"><a
                                class="d-flex align-items-center" href="{{ route('rp.merchant.payout.report') }}">
                                <svg width="7" height="11" viewBox="0 0 7 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.378831 10.5282C-0.0116932 10.1376 -0.0116932 9.50446 0.378831 9.11394L3.67172 5.82104L0.378831 2.52815C-0.0116936 2.13763 -0.0116936 1.50446 0.378831 1.11394C0.769355 0.723414 1.40252 0.723414 1.79304 1.11394L5.79304 5.11394C6.18357 5.50446 6.18357 6.13763 5.79304 6.52815L1.79304 10.5282C1.40252 10.9187 0.769355 10.9187 0.378831 10.5282Z"
                                        fill="#B3ADAD" />
                                </svg>
                                <span class="menu-title text-truncate ps-1">
                                    Payout Reports</span>
                            </a></li>
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</div>
