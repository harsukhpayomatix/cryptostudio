<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ config('app.name') }} | Merchant @yield('title')</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ storage_asset('theme/images/favicon.png') }}">

    <link rel="stylesheet" href="{{ storage_asset('/theme/vendor/select2/css/select2.min.css') }}">
    <link href="{{ storage_asset('theme/css/style.css') }}" rel="stylesheet">
    <link href="{{ storage_asset('theme/css/custom.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ storage_asset('/theme/vendor/toastr/css/toastr.min.css') }}">
    <link href="{{ storage_asset('ThemeCryptoStudio/assets/alertifyjs/css/alertify.min.css') }}" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.css"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        #preloader {
            z-index: 1000 !important;
        }

        .content-body {
            margin-left: 0px !important;
        }
    </style>
    @yield('customeStyle')
</head>

<body>
    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper" class="show">
        <!--**********************************
            Header start
        ***********************************-->
        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="{{ route('dashboardPage') }}" class="brand-logo">
                <img class="brand-title" src="{{ storage_asset('theme/images/Logo.png') }}" alt="" width="250px">
            </a>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                @yield('breadcrumbTitle')
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
            Footer start
        ***********************************-->
        @include('layouts.user.footer')
        <!--**********************************
            Footer end
        ***********************************-->

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ storage_asset('theme/vendor/global/global.min.js') }}"></script>
    <script src="{{ storage_asset('theme/js/custom.min.js') }}"></script>
    <script src="{{ storage_asset('theme/js/deznav-init.js') }}"></script>
    <script src="{{ storage_asset('theme/vendor/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptoStudio/assets/custom_js/bootstrap-datepicker.js') }}"></script>
    <?php /* <script src="{{ storage_asset('js/moment.min.js') }}"></script>*/ ?>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script src="{{ storage_asset('ThemeCryptoStudio/assets/custom_js/custom.js') }}"></script>

    <script src="{{ storage_asset('theme/vendor/toastr/js/toastr.min.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptoStudio/assets/alertifyjs/alertify.min.js') }}"></script>

    @yield('customScript')
</body>

</html>