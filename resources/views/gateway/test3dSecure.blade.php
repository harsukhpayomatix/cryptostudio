<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name') }} | Test 3D Secure Authentication Page</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ storage_asset('theme/images/favicon.png') }}">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ storage_asset('ThemeCryptostudio/css/typography.css') }}">
    <link href="{{ storage_asset('ThemeCryptostudio/css/custom.css') }}" rel="stylesheet">
    <style type="text/css" media="screen">
        .form-group label {
            color: #000;
        }

        .select {
            margin-bottom: 15px;
        }

        .page-background {
            padding: 2em;
            border-radius: 5px;
            box-shadow: rgb(0 0 0 / 70%) 10px 10px 15px -5px, rgb(0 0 0 / 60%) 5px 5px 5px -10px;
            background-color: #2B2B2B;
            margin-top: 15px;
        }

        .black-btn {
            background-color: #000;
            color: #fff;
        }

        .form-control {
            border-radius: 0px;
        }

        .btn-primary:focus,
        .btn-primary:hover,


        .text-white {
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container" style="padding-top: 10rem;">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 text-center text-white">
                <img src="{{ storage_asset('ThemeCryptostudio/images/finvert.png') }}" class="mb-2" />

                <h3 class="mt-4 mb-1">Welcome to Finvert Simulator API</h3>
                <p>Select the response which you want to receive.</p>
            </div>
            <div class="col-md-4 col-md-offset-4 text-center page-background">
                Select response
                <br />
                Amount: <strong>{{ $request_data['amount']. ' ' .$request_data['currency'] }}</strong><br />

                <form action="{{ route('test-stripe-submit',$session_id) }}" method="post" style="margin-top: 0.5em;">
                    @csrf
                    <select name="status" class="select form-control">
                        <option value="1">Success</option>
                        <option value="0">Declined</option>
                    </select>
                    <button type="submit" class="btn btn-md btn-block btn-primary black-btn">Continue</button>
                </form>
            </div>
        </div>
    </div>
    <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>