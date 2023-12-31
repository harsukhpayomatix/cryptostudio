<!DOCTYPE html>
<html class="loading dark-layout" lang="en" data-textdirection="ltr">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | RP - @yield('title')</title>
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

    @yield('customStyle')
    <link rel="stylesheet" type="text/css" href="{{ storage_asset('NewTheme/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ storage_asset('NewTheme/css/custom.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script type="text/javascript">
        var current_page_url = "<?php echo URL::current(); ?>";
        var current_page_fullurl = "<?php echo URL::full(); ?>";
        var CSRF_TOKEN = '{{ csrf_token() }}';
    </script>
</head>

<body
    class="horizontal-layout horizontal-menu navbar-floating footer-static menu-expanded { Auth::guard('agentUser')->user()->theme == 0 ? 'dark-layout' : 'light-layout' }}"
    data-open="hover" data-menu="horizontal-menu" data-col="">
    <div id="loading">
        <p>Loading..</p>
    </div>
    @php
        $currentPageURL = URL::current();
        $pageArray = explode('/', $currentPageURL);
        $pageActive = isset($pageArray[4]) ? $pageArray[4] : 'dashboard';
        $notifications = getNotifications(Auth::guard('agentUser')->user()->id, 'user', 5);
        $count_notifications = count($notifications);
    @endphp
    @include('layouts.agent.header')
    @include('layouts.agent.sidebar')

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper p-0">
            @yield('content')
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('layouts.agent.footer')

    <script src="{{ storage_asset('NewTheme/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ storage_asset('NewTheme/js/app-menu.js') }}"></script>
    <script src="{{ storage_asset('NewTheme/js/app.js') }}"></script>
    <script src="{{ storage_asset('NewTheme/vendors/js/extensions/toastr.min.js') }}"></script>

    <script src="{{ storage_asset('NewTheme/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ storage_asset('NewTheme/js/form-select2.js') }}"></script>



    <!-- Flatpicker Js -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Custom JavaScript -->
    <?php /* <script src="{{ storage_asset('ThemeCryptoStudio/js/moment.min.js') }}"></script>*/ ?>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>




    <script>
        $(document).ready(function() {
            $("#loading").hide();

            $('.nav-item.has-sub.sidebar-group-active').removeClass('open');
            // Add date picker class
            $(".date-input input").addClass("flatpicker")
            // For A Delete Record Popup
            $('body').on('click', '.delete_modal', function() {
                var id = $(this).attr('data-id');
                var url = $(this).attr('data-url');
                var token = CSRF_TOKEN;
                $(".remove-record-model").attr("action", url);
                $('body').find('.remove-record-model').append('<input name="_token" type="hidden" value="' +
                    token + '">');
                $('body').find('.remove-record-model').append(
                    '<input name="_method" type="hidden" value="DELETE">');
                $('body').find('.remove-record-model').append('<input name="id" type="hidden" value="' +
                    id + '">');
            });
            $('.remove-data-from-delete-form').click(function() {
                $('body').find('.remove-record-model').find("input").remove();
            });
            $('.modal').click(function() {
                // $('body').find('.remove-record-model').find( "input" ).remove();
            });

            // Applied date picker
            $(".flatpicker").flatpickr({
                dateFormat: "d-m-Y",
            });
        });
    </script>

    <script type="text/javascript">
        $('.modal').on('hidden.bs.modal', function(e) {
            jQuery('.chatbox').removeClass('active');
            jQuery('body').css('overflow', 'auto');
        });

        $(document).ready(function() {
            $('.select2').select2();

            $('#searchModal .select2').select2({
                dropdownParent: $('#searchModal')
            });

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

    @include('layouts.agent.alert')
    @include('layouts.agent.deleteModal')
    @yield('customScript')
</body>

</html>
