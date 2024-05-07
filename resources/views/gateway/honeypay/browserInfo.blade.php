<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Please do not refresh the page</title>

    <style>
        body {
            background-color: #f8f8f8 !important;
            color: #317A7C !important;
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
            border: 5px solid #f8f8f8;
            border-bottom-color: #317A7C;
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
    </style>
</head>

<body>
    <div id="loadingDiv">
        <span class="loader"></span>
        <p>Loading ...</p>
        <strong>Please do not refresh the page</strong>
    </div>
    <form method="POST" action="{{ route('midsPay.storeBrowser.info') }}" id="browserInfoForm">
        @csrf
        <input type="hidden" name="session_id" value="{{ $id }}" />
        <input type="hidden" name="browser_info" id="browserInfo" />
    </form>

    <script type="text/javascript" src="{{ storage_asset('ThemeCryptoStudio/js/jquery-latest.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var userAgent = navigator.userAgent;
            var browserInfo = {
                'browser_color_depth': window.screen.colorDepth,
                'browser_ip': null, // Client-side JavaScript cannot retrieve the IP address directly
                'browser_java_enabled': false, // Java applets are no longer widely supported
                'browser_language': window.navigator.language,
                'browser_screen_height': window.screen.height,
                'browser_screen_width': window.screen.width,
                'browser_tz': Intl.DateTimeFormat().resolvedOptions().timeZone,
                'browser_user_agent': userAgent,
                'window_height': window.innerHeight,
                'window_width': window.innerWidth,
            };

            $("#browserInfo").val(JSON.stringify(browserInfo));
            $("#browserInfoForm").submit();
        });
    </script>
</body>

</html>
