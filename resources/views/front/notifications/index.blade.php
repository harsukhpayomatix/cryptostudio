@extends('layouts.user.default')

@section('title')
All Notifications
@endsection

@section('breadcrumbTitle')
<a href="{{ route('dashboardPage') }}">Dashboard</a> / All Notifications
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12 col-xxl-12 col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">All Notifications</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless table-striped">
                        @foreach($notifications as $notification)
                        <tr>
                            <td>
                                <a href="{{ url($notification->url) }}?for=read">
                                    <strong class="text-primary-3">{{ $notification->title }}</strong>
                                    <span class="text-dark-2"> &nbsp; | &nbsp; {{ convertDateToLocal($notification->created_at, 'd-m-Y / H:i:s')}}</span>

                                    <p class="text-primary mt-25 mb-0">{{ Str::limit($notification->body,120) }}</p>
                                </a>

                            </td>

                            <td>
                                <a href="{{ url($notification->url) }}?for=read" target="_blank"
                                class="btn btn-primary btn-sm">Go to
                                Link</a>

                                <a href="{{ route('merchant-read-notifications',$notification->id) }}"
                                class="btn btn-primary btn-sm">Show</a>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection