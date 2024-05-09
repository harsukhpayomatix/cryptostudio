<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wallet Address</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ storage_asset('NewTheme/images/favicon.png') }}">
    <link rel="stylesheet" type="text/css" href="{{ storage_asset('NewTheme/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ storage_asset('NewTheme/css/custom.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
        integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        body {
            background-color: #f8f8f8 !important;
            color: #B3ADAD !important;
        }

        #loadingDiv {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .loader {
            width: 60px;
            height: 60px;
            border: 5px solid #B3ADAD;
            border-bottom-color: #4F738E;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .mainDiv {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .formDiv {
            border-radius: 0.5rem;
            background: #FFF;
            min-width: 500px;
            padding: 10px;
            box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
        }
    </style>
</head>

<body class="h-100 ">
    <div id="loadingDiv">
        <span class="loader"></span>
        <p>Loading ...</p>
        <strong>Please do not refresh the page</strong>
    </div>
    <div class="mainDiv">
        <div class="formDiv  p-2 form-dark">
            <h5 class="text-primary ">Please perform crypto transaction on below wallet address.from your wallet.</h5>
            <div class="d-flex justify-content-center flex-column align-items-center   my-2">
                <div id="qrcode"></div>
                <h6 class="text-primary mt-2">Wallet Address - {{ $response['walletAddress'] }} </h6>
                <h3 class="text-primary mt-2">Amount - {{ $response['amountRequiredUnit'] }} </h3>
            </div>

            {{-- <div class="mb-2 ">
                <label>Wallet Address</label>
                <input type="text" readonly class="form-control" value="{{ $response['walletAddress'] }}" />
            </div>
            <div class="mb-2">
                <label>Transaction Amount</label>
                <input type="text" readonly class="form-control" value="{{ $response['amountRequiredUnit'] }}" />
            </div> --}}

            <p class="text-danger "><strong>Note:-</strong> When transaction process done.please click on below button.
            </p>

            <a href="{{ route('xamax.user.redirect', [$id]) }}"><button class="btn btn-danger w-100 redirect">Back to Merchant
                    side</button></a>

        </div>
    </div>



    <script type="text/javascript" src="{{ storage_asset('ThemeCryptoStudio/js/jquery-latest.min.js') }}"></script>
    <script type="text/javascript">
        var hash = "{{ $response['walletAddress'] }}";
        $(document).ready(function() {
            $("#loadingDiv").hide();

            new QRCode(document.getElementById("qrcode"),
                hash
            )
            function callApiEveryTwoSeconds() {
                generateAccessToken();
            }
            

            function generateAccessToken() {
                $.ajax({
                    url: "{{route('xamax.checkresponse')}}",
                    type: 'GET',
                    data: {
                        transaction_id: "{{$id}}",
                    },
                    success: function(response) {
                        if(response?.status){
                            $('.redirect').click();
                        }
                    },
                    error: function(xhr, status, error) {
                    }
                });
    }

   

    // Call the function initially and then every 4 seconds
    callApiEveryTwoSeconds(); 
    setInterval(callApiEveryTwoSeconds, 4000);

  
    // redirect user after 5 minutes
    setTimeout(()=>{
            $('.redirect').click();
        }, 300000);

    });
    </script>
</body>

</html>
