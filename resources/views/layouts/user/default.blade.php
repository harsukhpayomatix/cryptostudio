<!DOCTYPE html>
<html class="loading dark-layout" lang="en" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | Merchant - @yield('title')</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ storage_asset('NewTheme/images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"
        integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ storage_asset('NewTheme/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ storage_asset('NewTheme/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ storage_asset('NewTheme/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ storage_asset('NewTheme/css/horizontal-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ storage_asset('NewTheme/vendors/css/extensions/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ storage_asset('NewTheme/css/select2.min.css') }}">

    @yield('customeStyle')
    <link rel="stylesheet" type="text/css" href="{{ storage_asset('NewTheme/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ storage_asset('NewTheme/css/custom.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script type="text/javascript">
        var DATE = "{{ date('d-m-Y') }}";
        var current_page_url = "<?php echo URL::current(); ?>";
        var current_page_fullurl = "<?php echo URL::full(); ?>";
        var CSRF_TOKEN = "{{ csrf_token() }}";
    </script>

    <script>
        var clicky_site_ids = clicky_site_ids || [];
        clicky_site_ids.push(101164380);
    </script>
</head>

<body
    class="horizontal-layout horizontal-menu navbar-floating footer-static menu-expanded {{ Auth::user()->theme == 0 ? 'dark-layout' : 'light-layout' }}"
    data-open="hover" data-menu="horizontal-menu" data-col="">
    <div id="loading">
        <p>Loading..</p>
    </div>
    <?php
    $currentPageURL = URL::current();
    $pageArray = explode('/', $currentPageURL);
    $pageActive = isset($pageArray[3]) ? $pageArray[3] : 'dashboardPage';
    if (\Auth::check()) {
        if (\Auth::user()->main_user_id != '0') {
            $userID = \Auth::user()->main_user_id;
        } else {
            $userID = \Auth::user()->id;
        }
        $notifications = getNotifications($userID, 'user', 5);
        $count_notifications = count($notifications);
    }
    ?>

    @include('layouts.user.header')

    @include('layouts.user.sidebar')

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper p-0">
            @yield('content')
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('layouts.user.footer')

    <script src="{{ storage_asset('NewTheme/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ storage_asset('NewTheme/js/app-menu.js') }}"></script>
    <script src="{{ storage_asset('NewTheme/js/app.js') }}"></script>
    <script src="{{ storage_asset('NewTheme/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ storage_asset('NewTheme/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ storage_asset('NewTheme/js/form-select2.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.nav-item.has-sub.sidebar-group-active').removeClass('open');
            // Set the flatpcker class 
            $(".date-input input").addClass("flatpicker")
            $(document).on("change", ".custom-file-input", function() {
                var file_count = $(this)[0].files.length;
                if (file_count == 1) {
                    file = $(this)[0].files[0].name;
                    $(this).parent(".custom-file").find(".custom-file-label").html(file);
                } else {
                    $(this).parent(".custom-file").find(".custom-file-label").html(file_count +
                        " files selected");
                }
            });

            $(document).on('select2:open', () => {
                $(this).closest('.select2-search__field').focus();
            });

            // on first focus (bubbles up to document), open the menu
            $(document).on('focus', '.select2-selection.select2-selection--single', function(e) {
                $(this).closest(".select2-container").siblings('select:enabled').select2('open');
            });

            // steal focus during close - only capture once and stop propogation
            $('select.select2').on('select2:closing', function(e) {
                $(e.target).data("select2").$selection.one('focus focusin', function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>

    <script type="text/javascript">
        var datetime = null,
            date = null;

        var update = function() {
            date = moment.utc();
            datetime.html(date.format('dddd, DD/MM/YYYY, HH:mm:ss a'));
        };

        $(document).ready(function() {
            datetime = $('#datetime')
            update();
            setInterval(update, 1000);
        });
    </script>

    <script src="{{ storage_asset('ThemeCryptoStudio/custom_js/custom.js') }}"></script>
    <?php /* <script src="{{ storage_asset('ThemeCryptoStudio/js/moment.min.js') }}"></script>*/ ?>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script>
        window.hostname = '{{ env('LARAVEL_ECHO_HOST') }}';
        window.laravel_echo_port = '{{ env('LARAVEL_ECHO_PORT') }}';
        window.user_id = {{ auth()->user()->id }};
        window.user_type = 'user';
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>

    @if (auth()->user()->is_rate_sent == 1)
        <script type="text/javascript">
            $(document).ready(function() {
                $("#is_rate").trigger("click");
            });
        </script>
    @endif

    @if (auth()->user()->is_rate_sent == 1)
        <button type="button" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg" id="is_rate"
            style="display: none;"></button>
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="min-width: 1040px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><strong> Fee Schedule</strong></h4>
                    </div>
                    <div class="modal-body">
                        <p><strong>Congratulations.!</strong></p>
                        <p>
                            Your account has been 'Approved' with the below mentioned rates. <br>Click 'Accept' to
                            proceed.
                        </p>
                        @include('partials.user.user_fee')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success rateAgree btn-sm" data-id="2">Accept</button>
                        <button type="button" class="btn btn-danger rateAgree btn-sm" data-id="3">Decline</button>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg1" id="is_rate_reason"
            style="display: none;"></button>
        <div class="modal fade bd-example-modal-lg1" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Decline Reason</h5>
                    </div>
                    <div class="modal-body">
                        <textarea id="reclineReason" class="form-control" name="reclineReason"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger rateAgreeReason btn-sm">Decline</button>
                        <button type="button" class="btn btn-warning rateAgreeReasonBack btn-sm">Back</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @include('layouts.user.alert')
    @include('layouts.user.deleteModal')
    @yield('customScript')
</body>

</html>
