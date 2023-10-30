<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('app.name') }} | {{ __('messages.transactionSccess') }}</title>
    <link rel="shortcut icon" href="{{ storage_asset('ThemeCryptostudio/images/favicon.png') }}" />
    <link rel="stylesheet" href="{{ storage_asset('ThemeCryptostudio/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ storage_asset('ThemeCryptostudio/css/typography.css') }}">
    <link rel="stylesheet" href="{{ storage_asset('ThemeCryptostudio/css/style.css') }}">
    <link rel="stylesheet" href="{{ storage_asset('ThemeCryptostudio/css/responsive.css') }}">
    <link href="{{ storage_asset('ThemeCryptostudio/css/custom.css') }}" rel="stylesheet">
</head>

<body>
    <div class="mt-5 iq-maintenance">
        <div class="container-fluid p-0">
            <div class="row no-gutters">
                <div class="col-sm-12 text-center">
                    <div class="iq-maintenance">
                        <img src="{{ storage_asset('theme/images/Logo.png') }}" alt="" width="300px">
                        <h3 class="mt-4 mb-1">{{ __('messages.headingTest') }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-3">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card iq-mb-3">
                        <div class="card-body">
                            <div class="m-3">
                                <h4 class="card-title text-center">{{ __('messages.transactionSccess') }}</h4>
                                <p class="card-text text-center text-success">{{ $input['reason'] }}</p>
                            </div>
                            <a href="{{ $redirect_url }}"
                                class="m-1 btn btn-success btn-block">{{ __('messages.returnMerchantSite') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ storage_asset('ThemeCryptostudio/js/jquery-latest.min.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/js/popper.min.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/js/bootstrap.min.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/js/jquery.appear.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/js/countdown.min.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/js/waypoints.min.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/js/wow.min.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/js/apexcharts.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/js/slick.min.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/js/select2.min.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/js/owl.carousel.min.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/js/smooth-scrollbar.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/js/lottie.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/js/chart-custom.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptostudio/js/custom.js') }}"></script>
</body>

</html>
