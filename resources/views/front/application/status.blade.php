@extends('layouts.user.default')

@section('title')
My Application
@endsection

@section('breadcrumbTitle')
<a href="{{ route('dashboardPage') }}">Dashboard</a> / My Application
@endsection

@section('customeStyle')
@endsection

@section('content')
@if(\Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <div class="alert-body">
        {{ \Session::get('success') }}
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
{{ \Session::forget('success') }}

@if(isset($data))
@if($data->status == 2)
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-xl-1 col-xxl-1 text-center">
                        <i class="fa fa-book text-primary" style="font-size: 56px;"></i>
                    </div>
                    <div class="col-xl-11 col-xxl-11">
                        <h4 class="text-primary">Pending Application with {{ config('app.name') }}</h4>
                        <p class="mb-0">Please review the status note and re-submit your application.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@elseif($data->status == 12)
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <div class="header-title">
                    <h4 class="card-title">Login Successful | Email - {{ucwords(Auth::user()->email)}}</h4>
                </div>
            </div>
            <div class="card-body p30">
                <h4 class="mb-2">Hola! We’re so excited you are a part of CryptoStudio.</h4>
                <p>We love all our customers, and that includes you too ! We’re glad you’ve
                    chosen us, and we want to show our appreciation by giving you a dedicated Account Manager.</p>
                <p>We are passionate about our services , and we assure you a smooth and stress
                    free customer onboarding. We are building a perfect payments infrastructure and we promise you
                    would enjoy this journey towards effortless payment acceptance.</p>
                <p>Your account manager will contact you within 24 hours to help you complete
                    your application.</p>
                <p>Keep an eye on your inbox as we’ll be sending you the information related to
                    your account and API integration.</p>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body p30">
                <div class="row">
                    <div class="col-md-3">
                        <svg width="180" height="150" viewBox="0 0 201 139" fill="none" xmlns="http://www.w3.org/2000/svg" style="max-width: 100%;">
                            <rect x="33.459" y="102.199" width="134.313" height="26.8626" fill="#B3ADAD"/>
                            <rect x="30.1285" y="99.9911" width="138.729" height="30.159" stroke="#1B1919" stroke-width="2.3"/>
                            <rect x="35.6953" y="37.2805" width="122.001" height="61.5601" fill="#232323"/>
                            <path d="M158.814 42.8774L158.814 99.9604" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M12.1934 137.456H187.919" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M48.0131 131.3V138.016M36.8203 131.3V138.016" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M163.302 131.3V138.016M153.229 131.3V138.016" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M192.394 137.456H200.229M0.998047 137.456H8.83297" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M29.0113 31.1551H40.1426V39.5557C40.1426 41.682 38.4189 43.4057 36.2926 43.4057H32.8613C30.735 43.4057 29.0113 41.682 29.0113 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M42.4449 31.1551H54.6955V39.5557C54.6955 41.682 52.9718 43.4057 50.8455 43.4057H46.2949C44.1686 43.4057 42.4449 41.682 42.4449 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M56.9898 31.1551H68.1211V39.5557C68.1211 41.682 66.3974 43.4057 64.2711 43.4057H60.8398C58.7136 43.4057 56.9898 41.682 56.9898 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M70.4234 31.1551H82.674V39.5557C82.674 41.682 80.9503 43.4057 78.824 43.4057H74.2734C72.1471 43.4057 70.4234 41.682 70.4234 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M84.9762 31.1551H96.1075V39.5557C96.1075 41.682 94.3838 43.4057 92.2575 43.4057H88.8262C86.6999 43.4057 84.9762 41.682 84.9762 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M98.4059 31.1551H110.656V39.5557C110.656 41.682 108.933 43.4057 106.806 43.4057H102.256C100.13 43.4057 98.4059 41.682 98.4059 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M112.957 31.1551H124.088V39.5557C124.088 41.682 122.364 43.4057 120.238 43.4057H116.807C114.68 43.4057 112.957 41.682 112.957 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M126.39 31.1551H138.641V39.5557C138.641 41.682 136.917 43.4057 134.791 43.4057H130.24C128.114 43.4057 126.39 41.682 126.39 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M140.935 31.1551H152.066V39.5557C152.066 41.682 150.343 43.4057 148.216 43.4057H144.785C142.659 43.4057 140.935 41.682 140.935 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M154.367 31.1551H166.617V39.5557C166.617 41.682 164.894 43.4057 162.767 43.4057H158.217C156.09 43.4057 154.367 41.682 154.367 39.5557V31.1551Z" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M35.6973 43.9962L35.6973 99.9599" stroke="#1B1919" stroke-width="2.3"/>
                            <rect x="114.041" y="86.5286" width="34.6975" height="12.312" fill="#B3ADAD"/>
                            <rect x="120.76" y="76.4551" width="23.5048" height="6.71564" fill="#F44336"/>
                            <path d="M111.805 98.8407V83.7305H149.3V98.8407" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M117.402 83.7304V74.2166H144.265V83.7304" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M88.3336 110.795C88.3336 109.773 89.1619 108.945 90.1836 108.945H106.569C107.591 108.945 108.419 109.773 108.419 110.795V118.226C108.419 119.248 107.591 120.076 106.569 120.076H90.1836C89.1619 120.076 88.3336 119.248 88.3336 118.226V110.795Z" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M88.3336 110.795C88.3336 109.773 89.1619 108.945 90.1836 108.945H106.569C107.591 108.945 108.419 109.773 108.419 110.795V118.226C108.419 119.248 107.591 120.076 106.569 120.076H90.1836C89.1619 120.076 88.3336 119.248 88.3336 118.226V110.795Z" fill="#F44336" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M50.2809 110.795C50.2809 109.773 51.1091 108.945 52.1309 108.945H68.5163C69.5381 108.945 70.3663 109.773 70.3663 110.795V118.226C70.3663 119.248 69.5381 120.076 68.5163 120.076H52.1309C51.1091 120.076 50.2809 119.248 50.2809 118.226V110.795Z" fill="#F44336" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M126.39 110.795C126.39 109.773 127.219 108.945 128.24 108.945H144.626C145.647 108.945 146.476 109.773 146.476 110.795V118.226C146.476 119.248 145.647 120.076 144.626 120.076H128.24C127.219 120.076 126.39 119.248 126.39 118.226V110.795Z" fill="#F44336" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M70.3984 114.51H87.1875" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M30.1016 114.51H49.1292" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M147.625 114.51H168.891" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M109.57 114.51H126.359" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M62.5875 65.8531H81.5537V71.9784C81.5537 77.2158 77.308 81.4615 72.0706 81.4615C66.8332 81.4615 62.5875 77.2158 62.5875 71.9784V65.8531Z" fill="#F44336" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M84.9145 65.2316L60.3519 65.2316L60.3519 61.9045C60.3519 55.1217 65.8504 49.6232 72.6332 49.6232C79.4159 49.6232 84.9145 55.1217 84.9145 61.9045L84.9145 65.2316Z" fill="#F44336" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M44.7184 99.3695C45.3112 90.0319 53.0724 82.6419 62.5596 82.6419L83.8259 82.6419C93.3131 82.6419 101.074 90.0319 101.667 99.3695L44.7184 99.3695Z" fill="#F44336" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M63.6758 82.0519V88.2079L72.63 94.9235L81.0245 87.6482V82.0519" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M42.714 4.82178L27.8535 30.5651H170.001L156.226 4.82178H42.714Z" fill="#F44336"/>
                            <path d="M40.1709 1.46399L23.9414 31.1248H169.447L155.456 1.46399H40.1709Z" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M36.8086 8.17969H158.25" stroke="#1B1919" stroke-width="2.3"/>
                        </svg>
                    </div>
                    <div class="col-md-9">
                        <h4 class="text-light-gray mb-2 mt-2">Start working on your application now!</h4>
                        <p class="mb-2">You'll be required to produce basic KYC documents.</p>
                        <ul class="mb-2 p-0" style="list-style: inside;">
                            <li class=""><i class="ri-check-line bg-info"></i>Business data </li>
                            <li class=""><i class="ri-check-line bg-info"></i>Shareholder data </li>
                            <li class=""><i class="ri-check-line bg-info"></i>Director data </li>
                        </ul>
                        <p>Your account will be ready to receive online payments once our team has approved the KYC submitted.</p>

                        <a href="{{ route('start-my-application') }}" class="btn btn-primary">
                            @if($isResume == 1)
                            Resume
                            @else
                            Proceed
                            @endif
                            &nbsp;
                            <svg width="7" height="11" viewBox="0 0 7 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.15422 9.80745C0.763697 9.41692 0.763697 8.78376 1.15422 8.39324L4.44711 5.10034L1.15422 1.80745C0.763697 1.41692 0.763697 0.783759 1.15422 0.393235C1.54475 0.00271058 2.17791 0.00271058 2.56843 0.393235L6.56844 4.39323C6.95896 4.78376 6.95896 5.41692 6.56844 5.80745L2.56844 9.80745C2.17791 10.198 1.54475 10.198 1.15422 9.80745Z" fill="#B3ADAD"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@else

<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <div class="header-title">
                    <h4 class="card-title">Login Successful | Email - {{ucwords(Auth::user()->email)}}</h4>
                </div>
            </div>
            <div class="card-body p30">
                <div class="blog-description">
                    <h4 class="mb-2">Hola! We’re so excited you are a part of CryptoStudio.</h4>
                    <p>We love all our customers, and that includes you too ! We’re glad you’ve
                        chosen us, and we want to show our appreciation by giving you a dedicated Account Manager.</p>
                    <p>We are passionate about our services , and we assure you a smooth and stress
                        free customer onboarding. We are building a perfect payments infrastructure and we promise you
                        would enjoy this journey towards effortless payment acceptance.</p>
                    <p>Your account manager will contact you within 24 hours to help you complete
                        your application.</p>
                    <p>Keep an eye on your inbox as we’ll be sending you the information related to
                        your account and API integration.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body p30">
                <div class="row">
                    <div class="col-md-3">
                        <svg width="180" height="150" viewBox="0 0 201 139" fill="none" xmlns="http://www.w3.org/2000/svg" style="max-width: 100%;">
                            <rect x="33.459" y="102.199" width="134.313" height="26.8626" fill="#B3ADAD"/>
                            <rect x="30.1285" y="99.9911" width="138.729" height="30.159" stroke="#1B1919" stroke-width="2.3"/>
                            <rect x="35.6953" y="37.2805" width="122.001" height="61.5601" fill="#232323"/>
                            <path d="M158.814 42.8774L158.814 99.9604" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M12.1934 137.456H187.919" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M48.0131 131.3V138.016M36.8203 131.3V138.016" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M163.302 131.3V138.016M153.229 131.3V138.016" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M192.394 137.456H200.229M0.998047 137.456H8.83297" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M29.0113 31.1551H40.1426V39.5557C40.1426 41.682 38.4189 43.4057 36.2926 43.4057H32.8613C30.735 43.4057 29.0113 41.682 29.0113 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M42.4449 31.1551H54.6955V39.5557C54.6955 41.682 52.9718 43.4057 50.8455 43.4057H46.2949C44.1686 43.4057 42.4449 41.682 42.4449 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M56.9898 31.1551H68.1211V39.5557C68.1211 41.682 66.3974 43.4057 64.2711 43.4057H60.8398C58.7136 43.4057 56.9898 41.682 56.9898 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M70.4234 31.1551H82.674V39.5557C82.674 41.682 80.9503 43.4057 78.824 43.4057H74.2734C72.1471 43.4057 70.4234 41.682 70.4234 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M84.9762 31.1551H96.1075V39.5557C96.1075 41.682 94.3838 43.4057 92.2575 43.4057H88.8262C86.6999 43.4057 84.9762 41.682 84.9762 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M98.4059 31.1551H110.656V39.5557C110.656 41.682 108.933 43.4057 106.806 43.4057H102.256C100.13 43.4057 98.4059 41.682 98.4059 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M112.957 31.1551H124.088V39.5557C124.088 41.682 122.364 43.4057 120.238 43.4057H116.807C114.68 43.4057 112.957 41.682 112.957 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M126.39 31.1551H138.641V39.5557C138.641 41.682 136.917 43.4057 134.791 43.4057H130.24C128.114 43.4057 126.39 41.682 126.39 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M140.935 31.1551H152.066V39.5557C152.066 41.682 150.343 43.4057 148.216 43.4057H144.785C142.659 43.4057 140.935 41.682 140.935 39.5557V31.1551Z" fill="#B3ADAD" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M154.367 31.1551H166.617V39.5557C166.617 41.682 164.894 43.4057 162.767 43.4057H158.217C156.09 43.4057 154.367 41.682 154.367 39.5557V31.1551Z" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M35.6973 43.9962L35.6973 99.9599" stroke="#1B1919" stroke-width="2.3"/>
                            <rect x="114.041" y="86.5286" width="34.6975" height="12.312" fill="#B3ADAD"/>
                            <rect x="120.76" y="76.4551" width="23.5048" height="6.71564" fill="#F44336"/>
                            <path d="M111.805 98.8407V83.7305H149.3V98.8407" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M117.402 83.7304V74.2166H144.265V83.7304" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M88.3336 110.795C88.3336 109.773 89.1619 108.945 90.1836 108.945H106.569C107.591 108.945 108.419 109.773 108.419 110.795V118.226C108.419 119.248 107.591 120.076 106.569 120.076H90.1836C89.1619 120.076 88.3336 119.248 88.3336 118.226V110.795Z" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M88.3336 110.795C88.3336 109.773 89.1619 108.945 90.1836 108.945H106.569C107.591 108.945 108.419 109.773 108.419 110.795V118.226C108.419 119.248 107.591 120.076 106.569 120.076H90.1836C89.1619 120.076 88.3336 119.248 88.3336 118.226V110.795Z" fill="#F44336" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M50.2809 110.795C50.2809 109.773 51.1091 108.945 52.1309 108.945H68.5163C69.5381 108.945 70.3663 109.773 70.3663 110.795V118.226C70.3663 119.248 69.5381 120.076 68.5163 120.076H52.1309C51.1091 120.076 50.2809 119.248 50.2809 118.226V110.795Z" fill="#F44336" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M126.39 110.795C126.39 109.773 127.219 108.945 128.24 108.945H144.626C145.647 108.945 146.476 109.773 146.476 110.795V118.226C146.476 119.248 145.647 120.076 144.626 120.076H128.24C127.219 120.076 126.39 119.248 126.39 118.226V110.795Z" fill="#F44336" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M70.3984 114.51H87.1875" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M30.1016 114.51H49.1292" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M147.625 114.51H168.891" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M109.57 114.51H126.359" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M62.5875 65.8531H81.5537V71.9784C81.5537 77.2158 77.308 81.4615 72.0706 81.4615C66.8332 81.4615 62.5875 77.2158 62.5875 71.9784V65.8531Z" fill="#F44336" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M84.9145 65.2316L60.3519 65.2316L60.3519 61.9045C60.3519 55.1217 65.8504 49.6232 72.6332 49.6232C79.4159 49.6232 84.9145 55.1217 84.9145 61.9045L84.9145 65.2316Z" fill="#F44336" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M44.7184 99.3695C45.3112 90.0319 53.0724 82.6419 62.5596 82.6419L83.8259 82.6419C93.3131 82.6419 101.074 90.0319 101.667 99.3695L44.7184 99.3695Z" fill="#F44336" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M63.6758 82.0519V88.2079L72.63 94.9235L81.0245 87.6482V82.0519" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M42.714 4.82178L27.8535 30.5651H170.001L156.226 4.82178H42.714Z" fill="#F44336"/>
                            <path d="M40.1709 1.46399L23.9414 31.1248H169.447L155.456 1.46399H40.1709Z" stroke="#1B1919" stroke-width="2.3"/>
                            <path d="M36.8086 8.17969H158.25" stroke="#1B1919" stroke-width="2.3"/>
                        </svg>
                    </div>
                    <div class="col-md-9">
                        <h4 class="text-light-gray mb-2 mt-2">Start working on your application now!</h4>
                        <p class="mb-2">You'll be required to produce basic KYC documents.</p>
                        <ul class="mb-2 p-0" style="list-style: inside;">
                            <li class=""><i class="ri-check-line bg-info"></i>Business data </li>
                            <li class=""><i class="ri-check-line bg-info"></i>Shareholder data </li>
                            <li class=""><i class="ri-check-line bg-info"></i>Director data </li>
                        </ul>
                        <p>Your account will be ready to receive online payments once our team has approved the KYC submitted.</p>

                        @if(empty($data))
                        <a href="{{ route('start-my-application') }}" class="btn btn-primary">
                            Start Application

                            &nbsp;
                            <svg width="7" height="11" viewBox="0 0 7 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.15422 9.80745C0.763697 9.41692 0.763697 8.78376 1.15422 8.39324L4.44711 5.10034L1.15422 1.80745C0.763697 1.41692 0.763697 0.783759 1.15422 0.393235C1.54475 0.00271058 2.17791 0.00271058 2.56843 0.393235L6.56844 4.39323C6.95896 4.78376 6.95896 5.41692 6.56844 5.80745L2.56844 9.80745C2.17791 10.198 1.54475 10.198 1.15422 9.80745Z" fill="#B3ADAD"/>
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(!empty($data) && $data->status != 12)
<div class="row">
    <div class="col-xl-8 col-xxl-8">
        <div class="card height-auto">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">My Application</h4>
                </div>
            </div>
            <div class="card-body p-0">
                @include('partials.application.applicationShow')
            </div>
        </div>

        <div class="card height-auto">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">My Documents</h4>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive custom-table">
                    <table class="table table-borderless table-striped">
                        @if ($data->licence_document != null)
                        <td>Licence Document</td>
                        <td>
                            <a href="{{ getS3Url($data->licence_document) }}" target="_blank"
                                class="btn btn-primary btn-sm">Show</a>
                            <a href="{{ route('downloadDocumentsUploadeUser',['file'=>$data->licence_document]) }}"
                                class="btn btn-primary btn-sm">Download</a>
                        </td>
                        @endif

                        @if ($data->passport != null)
                        <tr>
                            <td>Passport</td>
                            <td>
                                <div class="row">
                                    @foreach (json_decode($data->passport) as $key => $passport )
                                    <div class="col-md-4">File - {{ $key +1 }}</div>
                                    <div class="col-md-8">
                                        <a href="{{ getS3Url($passport) }}" target="_blank" class="btn btn-primary btn-sm">Show</a>
                                        <a href="{{ route('downloadDocumentsUploadeUser',['file'=>$passport]) }}"
                                            class="btn btn-primary btn-sm">Download</a>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endif

                        @if ($data->latest_bank_account_statement != null)
                        <tr>
                            <td>Company's Bank Statement (last 180 days)</td>
                            <td>
                                <div class="row">
                                    @foreach (json_decode($data->latest_bank_account_statement) as $key => $bankStatement )
                                    <div class="col-md-4">File - {{ $key +1 }}</div>
                                    <div class="col-md-8">
                                        <a href="{{ getS3Url($bankStatement) }}" target="_blank"
                                            class="btn btn-primary btn-sm">Show</a>
                                        <a href="{{ route('downloadDocumentsUploadeUser',['file'=>$bankStatement]) }}"
                                            class="btn btn-primary btn-sm">Download</a>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endif

                        @if ($data->utility_bill != null)
                        <tr>
                            <td>Utility Bill</td>
                            <td>
                                <div class="row">
                                    @foreach (json_decode($data->utility_bill) as $key => $utilityBill )
                                    <div class="col-md-4">File - {{ $key +1 }}</div>
                                    <div class="col-md-8">
                                        <a href="{{ getS3Url($utilityBill) }}" target="_blank"
                                            class="btn btn-primary btn-sm">Show</a>
                                        <a href="{{ route('downloadDocumentsUploadeUser',['file'=>$utilityBill]) }}"
                                            class="btn btn-primary btn-sm">Download</a>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endif
                        
                        @if ($data->company_incorporation_certificate != null)
                        <tr>
                            <td>Articles Of Incorporation</td>
                            <td>
                                <a href="{{ getS3Url($data->company_incorporation_certificate) }}" target="_blank"
                                    class="btn btn-primary btn-sm">Show</a>
                                <a href="{{ route('downloadDocumentsUploadeUser',['file'=>$data->company_incorporation_certificate]) }}"
                                    class="btn btn-primary btn-sm">Download</a>
                            </td>
                        </tr>
                        @endif

                        @if ($data->domain_ownership != null)
                        <tr>
                            <td>Domain Ownership</td>
                            <td>
                                <a href="{{ getS3Url($data->domain_ownership) }}" target="_blank"
                                    class="btn btn-primary btn-sm">Show</a>
                                <a href="{{ route('downloadDocumentsUploadeUser',['file'=>$data->domain_ownership]) }}"
                                    class="btn btn-primary btn-sm">Download</a>
                            </td>
                        </tr>
                        @endif

                        @if ($data->owner_personal_bank_statement != null)
                        <tr>
                            <td>UBO's Bank Statement (last 90 days)</td>
                            <td>
                                <a href="{{ getS3Url($data->owner_personal_bank_statement) }}" target="_blank"
                                    class="btn btn-primary btn-sm">Show</a>
                                <a href="{{ route('downloadDocumentsUploadeUser',['file'=>$data->owner_personal_bank_statement]) }}"
                                    class="btn btn-primary btn-sm">Download</a>
                            </td>
                        </tr>
                        @endif

                        @if(isset($data->previous_processing_statement) && $data->previous_processing_statement != null)
                        <tr>
                            <td>
                                Processing History (if any)
                            </td>
                            <td>
                                <div class="row">
                                    @php
                                    $previous_processing_statement_files = json_decode($data->previous_processing_statement);
                                    @endphp
                                    <div class="col-md-12">
                                        <div class="row">
                                            @php
                                            $count = 1;
                                            @endphp
                                            @foreach($previous_processing_statement_files as $key => $value)
                                            <div class="col-md-4">File - {{ $count }}</div>
                                            <div class="col-md-8">
                                                <a href="{{ getS3Url($value) }}" target="_blank"
                                                    class="btn btn-primary btn-sm">Show</a>
                                                <a href="{{ route('downloadDocumentsUploadeUser',['file' => $value]) }}"
                                                    class="btn btn-primary btn-sm">Download</a>
                                            </div>
                                            @php
                                            $count++;
                                            @endphp
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif

                        @if ($data->moa_document != null)
                        <tr>
                            <td>MOA(Memorandum of Association) Document</td>
                            <td>
                                <a href="{{ getS3Url($data->moa_document) }}" target="_blank"
                                    class="btn btn-primary btn-sm">Show</a>
                                <a href="{{ route('downloadDocumentsUploadeUser',['file'=>$data->moa_document]) }}"
                                    class="btn btn-primary btn-sm">Download</a>
                            </td>
                        </tr>
                        @endif

                        @if(isset($data->extra_document) && $data->extra_document != null)
                        <tr>
                            <td>
                                Additional Document
                            </td>
                            <td>
                                <div class="row">
                                    @php
                                    $extra_document_files = json_decode($data->extra_document);
                                    @endphp
                                    <div class="col-md-12">
                                        <div class="row">
                                            @php
                                            $count = 1;
                                            @endphp
                                            @foreach($extra_document_files as $key => $value)
                                            <div class="col-md-4 mt-2">File - {{ $count }}</div>
                                            <div class="col-md-8 mb-2">
                                                <a href="{{ getS3Url($value) }}" target="_blank"
                                                    class="btn btn-primary btn-sm">Show</a>
                                                <a href="{{ route('downloadDocumentsUploadeUser',['file' => $value]) }}"
                                                    class="btn btn-primary btn-sm">Download</a>
                                            </div>
                                            @php
                                            $count++;
                                            @endphp
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-xxl-4">
        <div class="card height-auto">
            <div class="card-header">
                <div class="header-title">
                    <h4 class="card-title">Status</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9 mt-50">
                        @if($data->status == '1')
                        <i class="fa fa-circle text-info mr-1"></i>
                        In Progress
                        @elseif($data->status == '2')
                        <i class="fa fa-circle text-primary mr-1"></i>
                        Incomplete
                        @elseif($data->status == '3')
                        <i class="fa fa-circle text-danger mr-1"></i>
                        Rejected
                        @elseif($data->status == '4')
                        <i class="fa fa-circle text-success mr-1"></i>
                        Pre Approval
                        @elseif($data->status == '5')
                        <i class="fa fa-circle text-success mr-1"></i>
                        Agreement Received
                        @elseif($data->status == '6')
                        <i class="fa fa-circle text-success mr-1"></i>
                        Agreement Uploaded 
                        @elseif($data->status == '7')
                        <i class="fa fa-circle text-danger mr-1"></i>
                        Not Interested
                        @elseif($data->status == '8')
                        <i class="fa fa-circle text-danger mr-1"></i>
                        Terminated
                        @elseif($data->status == '9')
                        <i class="fa fa-circle text-danger mr-1"></i>
                        Decline
                        @elseif($data->status == '10')
                        <i class="fa fa-circle text-success mr-1"></i>
                        Rate Accepted
                        @elseif($data->status == '11')
                        <i class="fa fa-circle text-success mr-1"></i>
                        Signed Agreement
                        @elseif($data->status == '12')
                        <i class="fa fa-circle text-success mr-1"></i>
                        Save Draft
                        @endif
                    </div>
                    <div class="col-md-3">
                        @if($data->status == '2')
                        @if(\Auth::user()->main_user_id == '0')
                        <a href="{{ route('edit-my-application', $data->id) }}"
                            class="btn btn-primary pull-right">Resubmit</a>
                        @endif
                        @endif
                        @if($data->status == '1')
                        @if(\Auth::user()->main_user_id == '0')
                        <a href="{{ route('edit-my-application', $data->id) }}" class="btn btn-primary pull-right">Edit</a>
                        @endif
                        @endif
                    </div>
                    @if($data->status == '2')
                    @if(!empty($data->reason_reassign))
                    <div class="col-md-12 mt-3">
                        <h5 class="text-black fs-18">Reason</h5>
                        <code>{{ $data->reason_reassign }}</code>
                    </div>
                    @endif
                    @endif
                    @if($data->status == '3')
                    @if(!empty($data->reason_reject))
                    <div class="col-md-12 mt-3">
                        <h5 class="text-black fs-18">Reject Reason</h5>
                        <code>{{ $data->reason_reject }}</code>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
         @if (isset($data->agreement_sent))
            <div class="card height-auto mt-3 userAgreementsBox">
                 <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Received Agreement</h4>
                </div>
            </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{getS3Url($data->agreement_sent)}}" class="btn  badge-primary" target="_blank" ><i class="fa fa-eye"></i> Show Agreement</a>
                        <a href="{{ route('downloadUserAgreement',['file' => $data->agreement_sent]) }}" class="btn  badge-success" target="_blank"><i class="fa fa-download"></i> Download Agreement</a>
                        
                    </div>
                </div>
            </div>    
        @endif
         @if (isset($data->agreement_received))
            <div class="card height-auto mt-3 userAgreementsBox">
                 <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Uploaded Agreement</h4>
                </div>
            </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{getS3Url($data->agreement_received)}}" class="btn  badge-primary" target="_blank" ><i class="fa fa-eye"></i> Show Agreement</a>
                        <a href="{{ route('downloadUserAgreement',['file' => $data->agreement_received]) }}" class="btn  badge-success" target="_blank"><i class="fa fa-download"></i> Download Agreement</a>
                    </div>
                </div>
            </div>    
        @endif
    </div>
</div>
@endif
@endsection