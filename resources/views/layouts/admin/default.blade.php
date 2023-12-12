<!DOCTYPE html>
<html class="loading dark-layout" lang="en" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | Admin @yield('title')</title>
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

    <style type="text/css">
        .navigation li ul li.active a {
            margin-left: 0px;
        }

        .main-menu.menu-dark .navigation>li ul li>a {
            padding: 5px 0px 5px 15px;
        }

        .vertical-layout.vertical-menu-modern .main-menu .navigation .menu-content>li>a i {
            margin-right: 10px;
        }

        .main-menu.menu-dark .navigation li a {
            text-align: left;
        }

        .main-menu.menu-dark .navigation>li>ul li:not(.has-sub) {
            margin: 0px 10px 0px 5px;
        }

        .main-menu.menu-dark .navigation>li ul li ul a {
            padding: 10px 15px 10px 30px;
        }

        #searchModal .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }

        #searchModal .modal-body::-webkit-scrollbar {
            width: 7px;
        }

        #searchModal .modal-body::-webkit-scrollbar-track {
            background: #F4F5F9;
        }

        #searchModal .modal-body::-webkit-scrollbar-thumb {
            background: #4F738E;
        }
    </style>
</head>

<body
    class="horizontal-layout horizontal-menu navbar-floating footer-static menu-expanded {{ Auth::guard('admin')->user()->theme == 0 ? 'dark-layout' : 'light-layout' }}"
    data-open="hover" data-menu="horizontal-menu" data-col="">
    <div id="loading">
        <p>Loading..</p>
    </div>
    <?php
    $currentPageURL = URL::current();
    $pageArray = explode('/', $currentPageURL);
    $pageActive = isset($pageArray[4]) ? $pageArray[4] : 'dashboard';
    $notifications = getNotificationsForAdmin();
    $count_notifications = count($notifications);
    
    $pageActive1 = \Request::route()->getName();
    ?>


    @include('layouts.admin.header')
    @include('layouts.admin.sidebar')

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper p-0">
            @yield('content')
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('layouts.admin.footer')

    <script src="{{ storage_asset('NewTheme/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ storage_asset('NewTheme/js/app-menu.js') }}"></script>
    <script src="{{ storage_asset('NewTheme/js/app.js') }}"></script>
    <script src="{{ storage_asset('NewTheme/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="{{ storage_asset('NewTheme/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ storage_asset('NewTheme/js/form-select2.js') }}"></script>

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
            $('.select2').select2();

            $('#searchModal .select2').select2({
                dropdownParent: $('#searchModal')
            });

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
        });
    </script>

    <script src="{{ storage_asset('ThemeCryptoStudio/custom_js/custom.js') }}"></script>
    <?php /* <script src="{{ storage_asset('ThemeCryptoStudio/js/moment.min.js') }}"></script> */ ?>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>

    <script type="text/javascript">
        var DATE = "{{ date('d-m-Y') }}";
        var current_page_url = "<?php echo URL::current(); ?>";
        var current_page_fullurl = "<?php echo URL::full(); ?>";
        var CSRF_TOKEN = "{{ csrf_token() }}";
    </script>

    <script>
        window.hostname = '{{ env('LARAVEL_ECHO_HOST') }}';
        window.laravel_echo_port = '{{ env('LARAVEL_ECHO_PORT') }}';
        window.user_id = {{ auth()->guard('admin')->user()->id }};
        window.user_type = 'admin';
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>

    @include('layouts.user.alert')
    @include('layouts.user.deleteModal')
    @yield('customScript')
</body>

</html>
