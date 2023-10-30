<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('app.name') }} | Admin Login</title>
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
    <ul class="circles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
    <!-- loader Start -->
    <div id="loading">
        <p class="mt-1">Loading...</p>
    </div>
    <!-- loader END -->
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="container">
            <div class="row m-0">
                <div class="col-md-8 col-xl-8 col-xxl-6 offset-md-2 offset-xl-2 offset-xxl-3 content-body">
                    <div class="row content-box-form">
                        <div class="col-md-4 content-box-left">
                            <img src="{{ storage_asset('NewTheme/images/finvert.png') }}" class="auth-logo">
                            <h3>Beyond Card Payment</h3>
                            <p>Changing the way you receive and pay.</p>

                            <div class="row">
                                <div class="col-md-12 text-center mt-1">
                                    <img src="{{ storage_asset('NewTheme/images/auth-img.svg') }}" width="150px">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 form-contant-right">
                            <h3>Sign In</h3>
                            <p>Enter your email address and password to access your account.</p>
                            <form action="{{ URL::route('admin/login') }}" id="login-form" method="post">
                                {!! csrf_field() !!}
                                @if (\Session::get('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <div class="alert-body">
                                            {{ \Session::get('success') }}
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
                                {{ \Session::forget('success') }}
                                @if (\Session::get('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <div class="alert-body">
                                            {{ \Session::get('error') }}
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
                                {{ \Session::forget('error') }}
                                <div class="form-group mt-1">
                                    <label class="form-label">Email address</label>
                                    <input type="text" class="form-control" placeholder="E-mail" name="email"
                                        autofocus="" tabindex="1">
                                </div>
                                @if ($errors->has('email'))
                                    <div class="error-input">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif

                                <div class="form-group mt-1">
                                    <label class="form-label">Password</label>
                                    <div class="input-group rounded-input form-password-toggle">
                                        <input type="password" name="password" class="form-control" id="password"
                                            placeholder="Password" tabindex="2">
                                        <span id="pwd-show" class="input-group-text cursor-pointer"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-eye font-small-4">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg></span>
                                    </div>
                                </div>
                                @if ($errors->has('password'))
                                    <div class="error-input">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-12 mt-1 text-right">
                                        <a href="{{ route('admin-password-reset') }}" class="text-primary">Forgot
                                            Password?</a>
                                    </div>
                                    <div class="col-md-12 mt-1">
                                        <button class="btn btn-danger w-100 g-recaptcha" tabindex="4"
                                            data-sitekey="{{ config('app.captch_sitekey') }}"
                                            data-callback="onSubmit" data-action="submit">Sign In</button>
                                    </div>
                                </div>
                            </form>
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

    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery("#load").fadeOut();
            jQuery("#loading").delay().fadeOut("");
        });
        $('#pwd-show').on('click', function() {
            var pwd = $('#password').attr('type');
            if (pwd == 'password') {
                $('#password').attr('type', 'text');
            } else {
                $('#password').attr('type', 'password');
            }
        });
        $("#password").focus(function() {
            $(this).attr('type', 'password');
        });
    </script>
    <script>
        function onSubmit(token) {
            document.getElementById("login-form").submit();
        }
    </script>
</body>

</html>
