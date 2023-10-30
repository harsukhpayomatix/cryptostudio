<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('app.name') }} | Merchant Confirm Mail Active</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ storage_asset('NewTheme/images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"
        integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- BEGIN: Theme CSS-->

    <link rel="stylesheet" type="text/css" href="{{ storage_asset('NewTheme/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ storage_asset('NewTheme/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ storage_asset('NewTheme/css/auth.css') }}">
</head>

<body>
    <!-- loader Start -->
    <div id="loading">
        <p class="mt-1">Loading...</p>
    </div>
    <!-- loader END -->
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="container">
            <div class="row m-0">
                <div class="col-md-4 col-xl-4 col-xxl-4 offset-md-4 offset-xl-4 offset-xxl-4 content-body">
                    <div class="row content-box-form">
                        <div class="col-md-12 text-center mb-2">
                            <img src="{{ storage_asset('NewTheme/images/finvert.png') }}" width="160px">
                        </div>
                        <div class="col-md-12 form-contant-right">
                            <h4 class="text-white mb-3">Thank You!</h4>
                            @if (\Session::get('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <div class="alert-body">
                                        {!! \Session::get('success') !!}
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-12 mt-2">
                            <p class="text-center">Back To <a href="{{route('login')}}" class="text-primary"> Sign In </a> </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js'></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>

    <script>
        jQuery(document).ready(function() {
            jQuery("#load").fadeOut();
            jQuery("#loading").delay().fadeOut("");
        });
    </script>
</body>

</html>
