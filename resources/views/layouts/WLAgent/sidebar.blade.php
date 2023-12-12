<!-- BEGIN: Main Menu-->
<div class="horizontal-menu-wrapper">
<div class="header-navbar navbar-expand-sm navbar navbar-horizontal floating-nav navbar-light navbar-shadow menu-border" role="navigation" data-menu="menu-wrapper" data-menu-type="floating-nav">
    <div class="navbar-container main-menu-content" data-menu="menu-container">
        <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="{{ $pageActive == 'dashboard' ? 'active' : '' }} nav-item">
                <a href="{{ route('wl-dashboard') }}" class="nav-link dropdown-item d-flex align-items-center">
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

            <li
                class="{{ $pageActive == 'merchant-management' || $pageActive == 'merchant-create' || $pageActive == 'merchant-edit' || $pageActive == 'merchant-show' ? 'active' : '' }} nav-item">
                <a href="{{ route('wl-merchant-management') }}" class="nav-link dropdown-item d-flex align-items-center">
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

            <li
                class="dropdown nav-item {{ $pageActive == 'merchant-transaction' ||
                $pageActive == 'merchant-chargebacks-transaction' ||
                $pageActive == 'merchant-refund-transaction' ||
                $pageActive == 'merchant-marked-transaction' ||
                $pageActive == 'merchant-retrieval-transaction' ||
                $pageActive == 'wl-merchant-transactions-show' ||
                $pageActive == 'merchant-crypto-transaction' ||
                $pageActive == 'merchant-declined-transaction' ||
                $pageActive == 'merchant-test-transaction'
                    ? 'active'
                    : '' }}" data-menu="dropdown">
                <a href="#" class="dropdown-toggle nav-link dropdown-item d-flex align-items-center" data-bs-toggle="dropdown">
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
                <ul class="dropdown-menu" data-bs-popper="none">
                    <li class="{{ $pageActive == 'merchant-transaction' ? 'active' : '' }}"><a
                            href="{{ route('wl-merchant-transaction') }}" class="dropdown-item dropdown-item d-flex align-items-center">
                            <span class="menu-item text-truncate ps-1">All Transactions</span>
                        </a>
                    </li>
                    <li class="{{ $pageActive == 'merchant-crypto-transaction' ? 'active' : '' }}"><a
                            href="{{ route('wl-merchant-transaction-crypto') }}" class="dropdown-item dropdown-item d-flex align-items-center">
                            <span class="menu-item text-truncate ps-1">Crypto Transactions</span>
                        </a>
                    </li>
                    <li class="{{ $pageActive == 'merchant-refund-transaction' ? 'active' : '' }}"><a
                            href="{{ route('wl-merchant-transaction-refund') }}" class="dropdown-item dropdown-item d-flex align-items-center">
                            <span class="menu-item text-truncate ps-1">Refunds</span>
                        </a>
                    </li>
                    <li class="{{ $pageActive == 'merchant-chargebacks-transaction' ? 'active' : '' }}"><a
                            href="{{ route('wl-merchant-transaction-chargebacks') }}"
                            class="dropdown-item dropdown-item d-flex align-items-center">
                            <span class="menu-item text-truncate ps-1">Chargebacks</span>
                        </a>
                    </li>
                    <li class="{{ $pageActive == 'merchant-retrieval-transaction' ? 'active' : '' }}"><a
                            href="{{ route('wl-merchant-transaction-retrieval') }}"
                            class="dropdown-item dropdown-item d-flex align-items-center">
                            <span class="menu-item text-truncate ps-1">Retrieval</span>
                        </a>
                    </li>
                    <li class="{{ $pageActive == 'merchant-marked-transaction' ? 'active' : '' }}"><a
                            href="{{ route('wl-merchant-transaction-suspicious') }}"
                            class="dropdown-item dropdown-item d-flex align-items-center">
                            <span class="menu-item text-truncate ps-1">Marked Transaction</span>
                        </a>
                    </li>
                    <li class="{{ $pageActive == 'merchant-declined-transaction' ? 'active' : '' }}"><a
                            href="{{ route('wl-merchant-transaction-declined') }}" class="dropdown-item dropdown-item d-flex align-items-center">
                            <span class="menu-item text-truncate ps-1">Declined</span>
                        </a>
                    </li>
                    <li class="{{ $pageActive == 'merchant-test-transaction' ? 'active' : '' }}"><a
                            href="{{ route('wl-merchant-transaction-test') }}" class="dropdown-item dropdown-item d-flex align-items-center">
                            <span class="menu-item text-truncate ps-1"> Test transactions</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li
                class="dropdown nav-item {{ $pageActive == 'wl-payout-report' ||
                $pageActive == 'transaction-summary-reports' ||
                $pageActive == 'summary-reports' ||
                $pageActive == 'user-card-summary-report' ||
                $pageActive == 'user-payment-status-summary-report'
                    ? 'active'
                    : '' }}" data-menu="dropdown">
                <a href="#" class="dropdown-toggle nav-link dropdown-item d-flex align-items-center" data-bs-toggle="dropdown">
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
                <ul class="dropdown-menu" data-bs-popper="none">
                    <li class="{{ $pageActive == 'wl-payout-report' ? 'active' : '' }}"><a
                            href="{{ route('wl-payout-report') }}" class="dropdown-item dropdown-item d-flex align-items-center">
                            <span class="menu-title text-truncate ps-1"> Payout Report</span>
                        </a>
                    </li>
                    <li class="{{ $pageActive == 'transaction-summary-reports' ? 'active' : '' }}"><a
                            href="{{ route('wl-transaction-summary-reports') }}" class="dropdown-item dropdown-item d-flex align-items-center">

                            <span class="menu-title text-truncate ps-1"> Transaction Summary Report</span>
                        </a>
                    </li>
                    <li
                        class="{{ $pageActive == 'summary-reports' || $pageActive == 'user-card-summary-report' || $pageActive == 'user-payment-status-summary-report' ? 'active' : '' }}">
                        <a href="{{ route('wl-summary-reports') }}" class="dropdown-item dropdown-item d-flex align-items-center">
                            <span class="menu-title text-truncate ps-1"> Summary Report</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
</div>
