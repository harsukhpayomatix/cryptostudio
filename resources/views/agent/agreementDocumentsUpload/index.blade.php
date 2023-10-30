<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ config('app.name') }} | Agreement Upload Document</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ storage_asset('theme/images/favicon.png') }}">

    <link rel="stylesheet" href="{{ storage_asset('ThemeCryptoStudio/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Typography CSS -->
    <link rel="stylesheet" href="{{ storage_asset('ThemeCryptoStudio/css/typography.css') }}">
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ storage_asset('ThemeCryptoStudio/css/style.css') }}">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ storage_asset('ThemeCryptoStudio/css/responsive.css') }}">

    <link rel="stylesheet" href="{{ storage_asset('ThemeCryptoStudio/css/flatpickr.min.css') }}">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="{{ storage_asset('ThemeCryptoStudio/css/custom.css') }}" rel="stylesheet">

    <style type="text/css">
        .grecaptcha-badge {
            z-index: 1000;
        }

        .auth-form .text-danger {
            color: #842e2e !important;
        }

        body {
            background-color: #34383E;
        }
    </style>

    <script type="text/javascript">
        var current_page_url = "<?php echo URL::current(); ?>";
        var current_page_fullurl = "<?php echo URL::full(); ?>";
        var CSRF_TOKEN = '{{ csrf_token() }}';
    </script>
    <script>
        var clicky_site_ids = clicky_site_ids || [];
        clicky_site_ids.push(101164380);
    </script>
    <script async src="//static.getclicky.com/js"></script>
</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-8">
                    <div class="text-center mb-3">
                        <a href="{{ route('login') }}">
                            <img src="{{ storage_asset('ThemeCryptoStudio/images/finvert.png') }}" alt=""
                                width="300px">
                        </a>
                    </div>
                    <div class="authincation-content pt-5 pb-5">
                        <div class="row no-gutters">
                            <div class="col-md-10 offset-md-1">
                                <div class="auth-form">
                                    @if (\Session::get('success'))
                                        <div class="alert alert-success alert-dismissible" role="alert">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert">×</button>
                                            <div class="alert-message">
                                                <span>{{ \Session::get('success') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    {{ \Session::forget('success') }}
                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert">×</button>
                                            <div class="alert-message">
                                                @foreach ($errors->all() as $error)
                                                    <p style="margin: 0px;">{{ $error }}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @if ($message = Session::get('error'))
                                        <div class="alert alert-warning alert-dismissible" role="alert">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert">×</button>
                                            <div class="alert-message">
                                                <span><strong>Error!</strong> {{ $message }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    {!! Session::forget('error') !!}
                                    <div class="iq-card">
                                        <div class="card-body br-25">
                                            <form class="" action="{{ route('rp-agreement-documents-upload') }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="rp_id" value="{{ $request->rpId }}">
                                                <input type="hidden" name="tokenId" value="{{ $request->token }}">
                                                <div
                                                    class="row form-group {{ $errors->has('files') ? ' has-error' : '' }}">
                                                    <div class="col-md-12">
                                                        <label class="control-label text-warning" for="files">Upload
                                                            Agreement</label>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file"
                                                                    class="custom-file-input filestyle" name="files"
                                                                    data-buttonname="btn-inverse"
                                                                    accept="image/png, image/jpeg, .pdf, .txt, .doc, .docx, .xls, .xlsx"
                                                                    id="inputGroupFile1">
                                                                <label class="custom-file-label"
                                                                    for="inputGroupFile1">Choose file</label>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('files'))
                                                            <p class="text-danger">
                                                                <strong>{{ $errors->first('files') }}</strong>
                                                            </p>
                                                        @endif
                                                        <br>
                                                        <button type="submit" class="btn btn-success">Upload</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ storage_asset('theme/vendor/global/global.min.js') }}"></script>
    <script src="{{ storage_asset('theme/js/custom.min.js') }}"></script>
    <script src="{{ storage_asset('theme/js/deznav-init.js') }}"></script>

    <script src="{{ storage_asset('ThemeCryptoStudio/js/jquery-latest.min.js') }}"></script>
    <script src="{{ storage_asset('ThemeCryptoStudio/custom_js/front/transactionDocumentsUpload.js') }}"></script>
</body>

</html>
